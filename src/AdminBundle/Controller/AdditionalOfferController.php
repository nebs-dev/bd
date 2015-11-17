<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\AdditionalOffer\AdditionalOffer;
use AppBundle\Entity\AdditionalOffer\Gallery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\AdditionalOffer\AdditionalOfferType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;



class AdditionalOfferController extends Controller {


    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->findBy(array(), array('validUntil' => 'ASC'));

        return $this->render('AdminBundle:AdditionalOffer:list.html.twig', array(
            'additionalOffers' => $additionalOffers
        ));
    }

    /**
     * Additional offers requests
     * @return Response
     */
    public function requestsAction() {
        $em = $this->getDoctrine()->getManager();
        $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->findBy(array('status' => 0), array('createdAt' => 'ASC'));

        return $this->render('AdminBundle:AdditionalOffer:list.html.twig', array(
            'additionalOffers' => $additionalOffers
        ));
    }

    /**
     * New Additional offer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $additionalOffer = new AdditionalOffer();

        $hosts = $em->getRepository('UserBundle:User')->getHosts();

        $form = $this->createForm(new AdditionalOfferType($request->getLocale(), $hosts), $additionalOffer);
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
            if(!$request->request->get('additional_offer')['validUntil']) {
                $additionalOffer->setValidUntil(new \DateTime('+1 year'));
            }
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

            return $this->redirect($this->generateUrl('admin_additional_offers'));
        }

        return $this->render('AdminBundle:AdditionalOffer:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Edit Additional offer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
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

        $hosts = $em->getRepository('UserBundle:User')->getHosts();

        $form = $this->createForm(new AdditionalOfferType($request->getLocale(), $hosts), $additionalOffer);
        $form->handleRequest($request);

        if($form->isValid()) {
            if(!$request->request->get('additional_offer')['validUntil']) {
                $additionalOffer->setValidUntil(new \DateTime('+1 year'));
            }
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

            return $this->redirect($this->generateUrl('admin_additional_offer_edit', array('id' => $additionalOffer->getId())));
        }

        return $this->render('AdminBundle:AdditionalOffer:edit.html.twig', array(
            'form'              => $form->createView(),
            'additionalOffer'   => $additionalOffer
        ));
    }

    public function deleteAction(Request $request, AdditionalOffer $additionalOffer) {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        $em->remove($additionalOffer);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_additional_offers', array('id' => $additionalOffer->getId())));
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

        return $this->redirect($this->generateUrl('admin_additional_offer_edit', array('id' => $additionalOffer->getId())));
    }

}