<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\AccommodationType;
use AdminBundle\Form\FeeType;
use AppBundle\Entity\Accommodation;
use AppBundle\Entity\AccommodationFee;
use AppBundle\Entity\AdditionalOffer\Gallery;
use AppBundle\Entity\AdvertisingPackage;
use AppBundle\Form\GalleryType;
use AppBundle\Form\VideoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AccommodationController extends Controller {

    /**
     * Get all Accommodations
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $accommodations = $em->getRepository('AppBundle:Accommodation')->findAll();
        return $this->render('AdminBundle:Accommodation:list.html.twig', array('accommodations' => $accommodations));
    }

    /**
     * New Accommodation
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $accommodation = new Accommodation();
        $hosts      = $em->getRepository('UserBundle:User')->getHosts();
        $countries = $em->getRepository('AppBundle:Country')->findAll();
//        $cities = $em->getRepository('AppBundle:City')->findAll();

        if(!isset($request->request->get('accommodation')['city']) && isset($request->request->get('accommodation')['name'])) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('city_should_not_be_blank'));
            return $this->redirect($this->generateUrl('admin_accommodation_new'));
        }

        $cityPost = $request->request->get('accommodation')['city'];
        $reqAccommodation = $request->request->get('accommodation');
        unset($reqAccommodation['city']);
        unset($reqAccommodation['region']);
        unset($reqAccommodation['subregion']);
        $request->request->set('accommodation', $reqAccommodation);

//            echo'<pre>';
//            exit(\Doctrine\Common\Util\Debug::dump($request->request->get('accommodation')));
//            echo'</pre>';

        // price for form
        if(!is_null($accommodation->getWifi())) {
            $newPrice = $this->get("currencyService")->fromEuro($accommodation->getWifi());
            $accommodation->setWifi(round($newPrice, 2));
        }

	    $form = $this->createForm(new AccommodationType($hosts, $request->getLocale(), true), $accommodation);
        $form->handleRequest($request);
        if($form->isValid()) {
            # All POST data
            $postData = $request->request->all();

            if(intval($postData['wifi']) == 0) {
                $accommodation->setWifi(null);
            } else {
                $accommodation->setWifi($this->get("currencyService")->toEuro($postData['accommodation']['wifi']));
            }
            $city = $em->getRepository('AppBundle:City')->find($cityPost);
            $accommodation->setCity($city);
            $em->persist($accommodation);
            $em->flush();

//            $advertisingPackage = new AdvertisingPackage();
//            $advertisingPackage->setAccommodation($accommodation);
//            $advertisingPackage->setStatus(0);
//

//            $em->persist($advertisingPackage);
//            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_accommodation'));
        }

        return $this->render('AdminBundle:Accommodation:new.html.twig', array(
            'form' => $form->createView(),
            'countries' => $countries
        ));
    }

    /**
     * Edit Accommodation
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id) {
        try {
            $em = $this->getDoctrine()->getManager();
            $session = $request->getSession();

            $accommodation  = $em->getRepository('AppBundle:Accommodation')->find($id);
            $hosts          = $em->getRepository('UserBundle:User')->getHosts();
            $countries      = $em->getRepository('AppBundle:Country')->findAll();
            $regions        = $em->getRepository('AppBundle:Region')->getByCountry($accommodation->getCity()->getSubregion()->getRegion()->getCountry()->getId());
            $subregions     = $em->getRepository('AppBundle:Subregion')->getByRegion($accommodation->getCity()->getSubregion()->getRegion()->getId());
            if($accommodation->getCity()->getSubregion()) {
                $cities         = $em->getRepository('AppBundle:City')->getBySubregion($accommodation->getCity()->getSubregion()->getId());
            } else {
                $cities         = $em->getRepository('AppBundle:City')->getByRegion($accommodation->getCity()->getRegion()->getId());
            }
            $gallery        = new Gallery();

            // price for form
            if(!is_null($accommodation->getWifi())) {
                $newPrice = $this->get("currencyService")->fromEuro($accommodation->getWifi());
                $accommodation->setWifi(round($newPrice, 2));
            }

            $form = $this->createForm(new AccommodationType($hosts, $request->getLocale()), $accommodation);
            $formGallery = $this->createForm(new GalleryType(), $gallery);
            $formVideo = $this->createForm(new VideoType());

            if(!is_null($request->request->get('accommodation')) && (!isset($request->request->get('accommodation')['city']) || $request->request->get('accommodation')['city'] == '')) {
                $session->getFlashBag()->add('msgError', $this->get('translator')->trans('city_should_not_be_blank'));
                return $this->redirect($this->generateUrl('admin_accommodation_edit', array('id' => $id)));
            }

            $cityPost = $request->request->get('accommodation')['city'];
            $reqAccommodation = $request->request->get('accommodation');
            unset($reqAccommodation['city']);
            $request->request->set('accommodation', $reqAccommodation);

            $form->handleRequest($request);
            if ($form->isValid()) {
                # All POST data
                $postData = $request->request->all();

                if(intval($postData['wifi']) == 0) {
                    $accommodation->setWifi(null);
                } else {
                    $accommodation->setWifi($this->get("currencyService")->toEuro($postData['accommodation']['wifi']));
                }
                $city = $em->getRepository('AppBundle:City')->find($cityPost);
                $accommodation->setCity($city);
                $em->persist($accommodation);
                $em->flush();

                $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

                return $this->redirect($this->generateUrl('admin_accommodation_edit', array('id' => $id)));
            }

            return $this->render('AdminBundle:Accommodation:edit.html.twig', array(
                'form' => $form->createView(),
                'formGallery' => $formGallery->createView(),
                'formVideo'     => $formVideo->createView(),
                'accommodation' => $accommodation,
                'countries'     => $countries,
                'regions'       => $regions,
                'subregions'    => $subregions,
                'cities'        => $cities
            ));
        } catch(\Exception $e) {
            return new Response($e);
        }
    }

    /**
     * Delete Accommodation
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $accommodation = $em->getRepository('AppBundle:Accommodation')->find($id);

        $session = $request->getSession();

        try {
            $em->remove($accommodation);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('accommodation_has_units'));
        }

        return $this->redirect($this->generateUrl('admin_accommodation'));
    }


}