<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\AccommodationCategoryType;
use AppBundle\Entity\AccommodationCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AccommodationCategoryController extends Controller {

    /**
     * List accommodation categories
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $accommodationCategories = $em->getRepository('AppBundle:AccommodationCategory')->findAll();
        return $this->render('AdminBundle:AccommodationCategory:list.html.twig', array('accommodationCategories' => $accommodationCategories));
    }

    /**
     * New Accommodation Category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $accommodationCategory = new AccommodationCategory();

        $form = $this->createForm(new AccommodationCategoryType(), $accommodationCategory);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em->persist($accommodationCategory);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_accommodation_category'));
        }

        return $this->render('AdminBundle:AccommodationCategory:new.html.twig', array('form' => $form->createView()));
    }

    /**
     * Delete Accommodation Category
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $accommodationCategory = $em->getRepository('AppBundle:AccommodationCategory')->find($id);

        $session = $request->getSession();

        try {
            $em->remove($accommodationCategory);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('category_have_accommodations'));
        }

        return $this->redirect($this->generateUrl('admin_accommodation_category'));
    }

}