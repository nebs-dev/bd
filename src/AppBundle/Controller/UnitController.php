<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitGallery;
use AppBundle\Entity\UnitPriceExtra;
use AppBundle\Form\UnitGalleryType;
use AppBundle\Form\UnitType;
use AppBundle\Form\PeriodType;
use AppBundle\Entity\UnitPeriod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UnitController extends Controller {

    /**
     * Get Units for accommodation
     * @return Response
     */
    public function indexAction($accommodationId) {
        $em = $this->getDoctrine()->getManager();
        $units = $em->getRepository('AppBundle:Unit')->getUnitsAccommodation($accommodationId);
        return $this->render('AppBundle:Unit:list.html.twig', array('units' => $units));
    }

    /**
     * New unit
     * @param Request $request
     * @param $accommodationId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request, $accommodationId) {
        $em = $this->getDoctrine()->getManager();
        $accommodation = $em->getRepository('AppBundle:Accommodation')->find($accommodationId);
        $unit = new Unit();


        $priceExtraRep = $em->getRepository('AppBundle:PriceExtra');
        $priceExtra = $priceExtraRep->findAll();

        $form = $this->createForm(new UnitType(), $unit);
        $form->handleRequest($request);

        # All POST data
        $postData = $request->request->all();
        if($form->isValid()) {
            $unit->setAccommodation($accommodation);
            $em->persist($unit);
            $em->flush();

            # Add price extra to join table with unit
            foreach($postData['priceExtra'] as $priceExtra) {
                if($priceExtra['availability']) {
                    $unitPriceExtra = new UnitPriceExtra();
                    $pExtra = $priceExtraRep->find($priceExtra['priceExtraId']);

                    $unitPriceExtra->setUnit($unit);
                    $unitPriceExtra->setPriceExtra($pExtra);
                    $unitPriceExtra->setPrice($priceExtra['price']);
                    $unitPriceExtra->setAvailability($priceExtra['availability']);

                    $em->persist($unitPriceExtra);
                    $em->flush();
                }
            }

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('app_accommodation_edit', array(
                'id' => $accommodationId
            )));
        }

        return $this->render('AppBundle:Unit:new.html.twig', array(
            'form'          => $form->createView(),
            'accommodation' => $accommodation,
            'priceExtra'    => $priceExtra
        ));
    }

    /**
     * Edit unit
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Unit $unit) {
        $em = $this->getDoctrine()->getManager();
        $period = new UnitPeriod();

        # If user isn't owner -> 403
        if($this->getUser()->getId() != $unit->getAccommodation()->getUser()->getId())
            return $this->redirect($this->generateUrl('user_403'));

        $unitPeriods = $em->getRepository('AppBundle:UnitPeriod')->getAll($unit->getId());
        $unitGallery = new UnitGallery();

        $priceExtraRep = $em->getRepository('AppBundle:PriceExtra');
        $priceExtra = $priceExtraRep->findAll();

        // price for form
        foreach($unit->getUnitPriceExtra() as $pe) {
            if(!is_null($pe->getPrice())) {
                $newPrice = $this->get("currencyService")->fromEuro($pe->getPrice());
                $pe->setPrice(round($newPrice, 2));
            }
        }

        # Unit form
        $form = $this->createForm(new UnitType(), $unit);
        # Period form
        $formPeriod = $this->createForm(new PeriodType($unit), $period);
        # Gallery form
        $formGallery = $this->createForm(new UnitGalleryType(array($unit)), $unitGallery);

        $form->handleRequest($request);

        # All POST data
        $postData = $request->request->all();
        if ($form->isValid()) {
            $em->persist($unit);
            $em->flush();

//            echo'<pre>';
//            exit(\Doctrine\Common\Util\Debug::dump($request->request->get('accommodation')['wifi']));
//            echo'</pre>';

            $em->getRepository('AppBundle:UnitPriceExtra')->destroyAllUnit($unit);
            # Add price extra to join table with unit
            foreach($postData['priceExtra'] as $priceExtra) {
                if($priceExtra['availability'] > 0) {
                    $unitPriceExtra = new UnitPriceExtra();
                    $pExtra = $priceExtraRep->find($priceExtra['priceExtraId']);

                    $unitPriceExtra->setUnit($unit);
                    $unitPriceExtra->setPriceExtra($pExtra);
                    $price = $this->get("currencyService")->toEuro($priceExtra['price']);
                    $unitPriceExtra->setPrice($price);
                    $unitPriceExtra->setAvailability($priceExtra['availability']);

                    $em->persist($unitPriceExtra);
                    $em->flush();
                }
            }

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

            return $this->redirect($this->generateUrl('app_unit_edit', array(
                'id' => $unit->getId(),
                'priceExtra'    => $priceExtra,
            )));
        }

        return $this->render('AppBundle:Unit:edit.html.twig', array(
            'form'          => $form->createView(),
            'unit'          => $unit,
            'periods'       => $unitPeriods,
            'formPeriod'    => $formPeriod->createView(),
            'formGallery'   => $formGallery->createView(),
            'priceExtra'    => $priceExtra,
        ));
    }

    /**
     * Delete unit
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Unit $unit) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        # If user isn't owner -> 403
        if($this->getUser()->getId() != $unit->getAccommodation()->getUser()->getId())
            return $this->redirect($this->generateUrl('user_403'));

        if(count($em->getRepository('AppBundle:Booking')->getBookingUnit($unit)) == 0) {
            $em->remove($unit);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } else {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('unit_has_bookings'));
        }

        return $this->redirect($this->generateUrl('app_accommodation_edit', array(
            'id' => $unit->getAccommodation()->getId()
        )));
    }

}