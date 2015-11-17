<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\BannerType;
use AppBundle\Entity\Banner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BannerController extends Controller {


    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $banners = $em->getRepository('AppBundle:Banner')->getAll();

        return $this->render('AdminBundle:Banner:list.html.twig', array(
            'banners' => $banners
        ));
    }

    /**
     * New Banner
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $banner = new Banner();

        $form = $this->createForm(new BannerType($request->getLocale()), $banner);
        $form->handleRequest($request);

        $country = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('country')));
        $region = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('region')));
        $subregion = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('subregion')));
        $city = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('city')));

        $countryObj = $this->get('locationService')->getLocationObject($this->get('locationService')->getLocationType($country), $country);
        $regionObj = $this->get('locationService')->getLocationObject($this->get('locationService')->getLocationType($region), $region);
        $subregionObj = $this->get('locationService')->getLocationObject($this->get('locationService')->getLocationType($subregion), $subregion);
        $cityObj = $this->get('locationService')->getLocationObject($this->get('locationService')->getLocationType($city), $city);

        if($form->isValid()) {
            $banner->setCountry($countryObj);
            $banner->setRegion($regionObj);
            $banner->setSubregion($subregionObj);
            $banner->setCity($cityObj);
            $em->persist($banner);
            $em->flush();

            $banner->upload();
            $em->persist($banner);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_banners'));
        }

        return $this->render('AdminBundle:Banner:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Delete Banner
     * @param Request $request
     * @param Banner $banner
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Banner $banner) {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        try {
            $em->remove($banner);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('delete_error'));
        }

        return $this->redirect($this->generateUrl('admin_banners'));
    }
}
