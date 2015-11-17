<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\UnitCategoryType;
use AdminBundle\Form\UnitType;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitCategory;
use AppBundle\Entity\UnitGallery;
use AppBundle\Entity\UnitPeriod;
use AppBundle\Entity\UnitPriceExtra;
use AppBundle\Entity\Accommodation;
use AppBundle\Form\PeriodType;
use AppBundle\Form\UnitGalleryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UnitController extends Controller {

    /**
     * List of Units
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $units = $em->getRepository('AppBundle:Unit')->getAll();
        return $this->render('AdminBundle:Unit:list.html.twig', array('units' => $units));
    }


    /**
     * New Unit
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $unit = new Unit();

        $priceExtraRep = $em->getRepository('AppBundle:PriceExtra');
        $priceExtra = $priceExtraRep->findAll();

        $form = $this->createForm(new UnitType(), $unit)->remove('accommodation');
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

            return $this->redirect($this->generateUrl('admin_accommodation_edit', array('id' => $accommodation->getId())));
        }

        return $this->render('AdminBundle:Unit:new.html.twig', array(
            'form' => $form->createView(),
            'accommodation' => $accommodation,
            'priceExtra'    => $priceExtra
        ));
    }

    /**
     * Edit Unit
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $unit = $em->getRepository('AppBundle:Unit')->find($id);
        $period = new UnitPeriod();
        $unitPeriods = $em->getRepository('AppBundle:UnitPeriod')->getAll($unit->getId());
        $unitGallery = new UnitGallery();

        $priceExtraRep = $em->getRepository('AppBundle:PriceExtra');
        $priceExtra = $priceExtraRep->findAll();

        $form = $this->createForm(new UnitType(), $unit)->remove('accommodation');

        # Period form
        $formPeriod = $this->createForm(new PeriodType($unit), $period);
        # Gallery form
        $formGallery = $this->createForm(new UnitGalleryType(array($unit)), $unitGallery);

        # All POST data
        $postData = $request->request->all();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($unit);
            $em->flush();

//            echo'<pre>';
//            exit(\Doctrine\Common\Util\Debug::dump($postData['priceExtra']));
//            echo'</pre>';

            $em->getRepository('AppBundle:UnitPriceExtra')->destroyAllUnit($unit);
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
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

            return $this->redirect($this->generateUrl('admin_unit_edit', array(
                'id' => $id,
                'unit' => $unit,
                'priceExtra' => $priceExtra
            )));
        }

        return $this->render('AdminBundle:Unit:edit.html.twig', array(
            'form' => $form->createView(),
            'unit' => $unit,
            'priceExtra' => $priceExtra,
            'periods'       => $unitPeriods,
            'formPeriod'    => $formPeriod->createView(),
            'formGallery'   => $formGallery->createView(),
        ));
    }

    /**
     * Delete Unit
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $unit = $em->getRepository('AppBundle:Unit')->find($id);
        $accommodation = $unit->getAccommodation();

        $session = $request->getSession();

        $em->remove($unit);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_accommodation_edit', array('id' => $accommodation->getId())));
    }

    ##################
    ### Categories ###
    ##################

    /**
     * List unit categories
     * @return Response
     */
    public function unitCatListAction() {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:UnitCategory')->findAll();
        return $this->render('AdminBundle:UnitCategory:list.html.twig', array('categories' => $categories));
    }

    /**
     * New Unit Category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function unitCatNewAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $uc = new UnitCategory();

        $form = $this->createForm(new UnitCategoryType(), $uc);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em->persist($uc);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_unit_category'));
        }

        return $this->render('AdminBundle:UnitCategory:new.html.twig', array('form' => $form->createView()));
    }

    /**
     * Delete Unit Category
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unitCatDeleteAction(Request $request, UnitCategory $uc) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            $em->remove($uc);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('category_have_units'));
        }

        return $this->redirect($this->generateUrl('admin_unit_category'));
    }


    ###############
    ### Periods ###
    ###############


    /**
     * New Period for unit
     * @param Request $request
     * @param $unitId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newPeriodAction(Request $request, Unit $unit) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $period = new UnitPeriod();

        $form = $this->createForm(new PeriodType($unit), $period);
        $form->handleRequest($request);

        if($form->isValid()) {
            $amount = $form->getData()->getAmount();
            $sign = $request->request->get('sign');

            $period->setUnit($unit);
            $period->setAmount(intval($sign . $amount));
            $em->persist($period);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));
        } else {
            $session->getFlashBag()->add('msgError', (string)$form->getErrors(true, false));
        }

        return $this->redirect($this->generateUrl('admin_unit_edit', array(
            'id' => $unit->getId()
        )));
    }

    /**
     * Delete period
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePeriodAction(Request $request, UnitPeriod $period) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $em->remove($period);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_unit_edit', array(
            'id' => $period->getUnit()->getId()
        )));
    }

}