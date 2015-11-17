<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AdditionalOffer\AdditionalOffer;
use AppBundle\Entity\AdditionalOffer\Gallery;
use AppBundle\Form\AdditionalOffer\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\AdditionalOffer\AdditionalOfferType;



class AdditionalOfferController extends Controller {

    /**
     * Additional offers list && search
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request, $category) {
        $em = $this->getDoctrine()->getManager();
        $aoRep = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer');

        $form = $this->createForm(new SearchType());
        $form->handleRequest($request);

        # If POST
        if($form->isValid()) {
            # Array with form fields
            $data = $form->getData();
//            print_r($data['category']->getName());

            $location = null;
            $locationType = null;
            if(!empty($data['location'])) {
                # Remove everything after first comma in location
                $location = preg_replace('/^([^,]*).*$/', '$1', trim($data['location']));
                $data['location'] = $location;

                # Location type for additional offers
                $locationType = $this->get('locationService')->getLocationType($location);
            }

            $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->searchByLocationCategory($location, $locationType, $data['category']);

        # If GET Category and no POST
        } elseif(!is_null($category)) {
            $location = null;
            $locationType = null;
            $category = $em->getRepository('AppBundle:AdditionalOffer\Category')->findOneBy(array('name' => $category));

//            exit(\Doctrine\Common\Util\Debug::dump($category));

            $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->searchByLocationCategory($location, $locationType, $category);

        # If no POST && GET
        } else {
            $additionalOffers = $aoRep->getAll();
        }

        return $this->render('AppBundle:AdditionalOffer:index.html.twig', array(
            'form' => $form->createView(),
            'additionalOffers' => $additionalOffers
        ));
    }

//    /**
//     * New Additional offer - User
//     * @param Request $request
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
//     */
//    public function newAction(Request $request) {
//        $em = $this->getDoctrine()->getManager();
//        $additionalOffer = new AdditionalOffer();
//
//        $form = $this->createForm(new AdditionalOfferType($request->getLocale()), $additionalOffer)
//            ->remove('status')
//            ->remove('validUntil');
//        $form->handleRequest($request);
//
//        $country = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('country')));
//        $region = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('region')));
//        $subregion = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('subregion')));
//        $city = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('city')));
//
//        $countryObj = $this->get('locationService')->getLocationObject($this->get('locationService')->getLocationType($country), $country);
//        $regionObj = $this->get('locationService')->getLocationObject($this->get('locationService')->getLocationType($region), $region);
//        $subregionObj = $this->get('locationService')->getLocationObject($this->get('locationService')->getLocationType($subregion), $subregion);
//        $cityObj = $this->get('locationService')->getLocationObject($this->get('locationService')->getLocationType($city), $city);
//
//        if($form->isValid()) {
//            if(!$request->request->get('additional_offer')['validUntil']) {
//                $additionalOffer->setValidUntil(new \DateTime('+1 year'));
//            }
//            if($countryObj) {
//                $additionalOffer->setCountry($countryObj);
//            }
//            if($regionObj) {
//                $additionalOffer->setRegion($regionObj);
//            }
//            if($subregionObj) {
//                $additionalOffer->setSubregion($subregionObj);
//            }
//            if($cityObj) {
//                $additionalOffer->setCity($cityObj);
//            }
//            $additionalOffer->setStatus(0);
//            $em->persist($additionalOffer);
//            $em->flush();
//
//            # Upload each photo
//            $data = $form->getData();
//            foreach($data->getGallery() as $gallery) {
////                if(empty($gallery->getFile())) die('Prazna slika!!!');
//                $gallery->upload();
//                $em->persist($gallery);
//                $em->flush();
//            }
//
//            $session = $request->getSession();
//            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));
//
//            return $this->redirect($this->generateUrl('app_additional_offers'));
//        }
//
//        return $this->render('AppBundle:AdditionalOffer:new.html.twig', array(
//            'form' => $form->createView()
//        ));
//    }


    /**
     * Single Additional offer
     * @param AdditionalOffer $additionalOffer
     * @return Response
     */
    public function singleAction(AdditionalOffer $additionalOffer) {

        return $this->render('AppBundle:AdditionalOffer:single.html.twig', array(
            'additionalOffer' => $additionalOffer
        ));
    }

    /**
     * Host list additional offers
     * @return Response
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->findByHost($this->getUser());

        return $this->render('AppBundle:AdditionalOffer:list.html.twig', array(
            'additionalOffers' => $additionalOffers
        ));
    }

    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $additionalOffer = new AdditionalOffer();

        $form = $this->createForm(new AdditionalOfferType($request->getLocale()), $additionalOffer)
        ->remove('validUntil')
        ->remove('status')
        ->remove('host');
        $form->handleRequest($request);

        $country = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('country')));
        $region = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('region')));
        $subregion = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('subregion')));
        $city = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('city')));

        $countryObj = $this->get('locationService')->getLocationObject('country', $country);
        $regionObj = $this->get('locationService')->getLocationObject('region', $region);
        $subregionObj = $this->get('locationService')->getLocationObject('subregion', $subregion);
        $cityObj = $this->get('locationService')->getLocationObject('city', $city);

        if($form->isValid()) {
            $additionalOffer->setValidUntil(new \DateTime('+1 year'));
            $additionalOffer->setStatus(false);
            $additionalOffer->setHost($this->getUser());
            if($countryObj) {
                $additionalOffer->setCountry($countryObj);
            }
            if($regionObj) {
                $additionalOffer->setRegion($regionObj);
            }
            if($subregionObj) {
                $additionalOffer->setSubregion($subregionObj);
            }
            if($cityObj) {
                $additionalOffer->setCity($cityObj);
            }

            $em->persist($additionalOffer);
            $em->flush();

            # Upload each photo
            $data = $form->getData();
            foreach($data->getGallery() as $gallery) {
                $gallery->upload();
                $em->persist($gallery);
                $em->flush();
            }

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('app_additional_offer_host_list'));
        }

        return $this->render('AppBundle:AdditionalOffer:new.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function editAction(Request $request, AdditionalOffer $additionalOffer) {
        $em = $this->getDoctrine()->getManager();

        $country = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('country')));
        $region = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('region')));
        $subregion = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('subregion')));
        $city = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('city')));

        $countryObj = $this->get('locationService')->getLocationObject('country', $country);
        $regionObj = $this->get('locationService')->getLocationObject('region', $region);
        $subregionObj = $this->get('locationService')->getLocationObject('subregion', $subregion);
        $cityObj = $this->get('locationService')->getLocationObject('city', $city);

        $form = $this->createForm(new AdditionalOfferType($request->getLocale()), $additionalOffer)
            ->remove('validUntil')
            ->remove('status')
            ->remove('host');
        $form->handleRequest($request);

        if($form->isValid()) {
            if($countryObj) {
                $additionalOffer->setCountry($countryObj);
            } else {
                $additionalOffer->setCountry(null);
            }
            if($regionObj) {
                $additionalOffer->setRegion($regionObj);
            } else {
                $additionalOffer->setRegion(null);
            }
            if($subregionObj) {
                $additionalOffer->setSubregion($subregionObj);
            } else {
                $additionalOffer->setSubregion(null);
            }
            if($cityObj) {
                $additionalOffer->setCity($cityObj);
            } else {
                $additionalOffer->setCity(null);
            }

            $em->persist($additionalOffer);
            $em->flush();

            # Upload each photo
            $data = $form->getData();
            foreach($data->getGallery() as $gallery) {
                $gallery->upload();
                $em->persist($gallery);
                $em->flush();
            }

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

            return $this->redirect($this->generateUrl('app_additional_offer_host_edit', array('id' => $additionalOffer->getId())));
        }

        return $this->render('AppBundle:AdditionalOffer:edit.html.twig', array(
            'form'              => $form->createView(),
            'additionalOffer'   => $additionalOffer
        ));
    }


    /**
     * Delete Additional offer photo
     * @param Request $request
     * @param Gallery $gallery
     * @param AdditionalOffer $additionalOffer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePhotoAction(Request $request, Gallery $gallery, $additionalOfferId) {
        $em = $this->getDoctrine()->getManager();

        $additionalOffer = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->find($additionalOfferId);

        $session = $request->getSession();

        $em->remove($gallery);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('app_additional_offer_host_edit', array('id' => $additionalOffer->getId())));
    }

}