<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Discover;
use AppBundle\Form\DiscoverType;
use AppBundle\Form\SpecialOfferType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class DiscoverController extends Controller {

    /**
     * List special offers
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $discover = $em->getRepository('AppBundle:Discover')->findAll();

        return $this->render('AdminBundle:Discover:list.html.twig', array(
            'discover' => $discover
        ));
    }

    /**
     * Add Special offer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $discover = new Discover();
        $session = $request->getSession();

        $form = $this->createForm(new DiscoverType(), $discover);
        $form->handleRequest($request);

        if($form->isValid()) {

            # Check number of discovers
            if(count($em->getRepository('AppBundle:Discover')->findAll()) >= 20) {
                $session->getFlashBag()->add('msgError', $this->get('translator')->trans('too_many_discovers'));
                return $this->redirect($this->generateUrl('admin_discover'));
            }

            $discover->upload();
            $em->persist($discover);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));
            return $this->redirect($this->generateUrl('admin_discover'));
        }

        return $this->render('AdminBundle:Discover:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Delete special offer
     * @param Request $request
     * @param Discover $specialOffer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Discover $discover) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $em->remove($discover);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_discover'));
    }

//    /**
//     * Change status of Special offer
//     * @param Request $request
//     * @param SpecialOffer $specialOffer
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function changeStatusAction(Request $request, SpecialOffer $specialOffer) {
//        $em = $this->getDoctrine()->getManager();
//        $session = $request->getSession();
//        $soRep = $em->getRepository('AppBundle:SpecialOffer');
//
//        # If change status from 0 to 1
//        if($specialOffer->getStatus() == 0) {
//            if(count($soRep->getNumByStatus(1)) >= 6) {
//                $session->getFlashBag()->add('msgError', $this->get('translator')->trans('status_change_error'));
//                return $this->redirect($this->generateUrl('admin_special_offers'));
//            }
//
//            $specialOffer->setStatus(1);
//        } else {
//            $specialOffer->setStatus(0);
//        }
//
//        $em->persist($specialOffer);
//        $em->flush();
//
//        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('status_change_success'));
//        return $this->redirect($this->generateUrl('admin_special_offers'));
//    }

}