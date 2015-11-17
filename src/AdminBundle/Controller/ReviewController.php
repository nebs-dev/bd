<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ReviewController extends Controller {

    /**
     * Get all Accommodations
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository('AppBundle:Review')->getAll();
        return $this->render('AdminBundle:Review:list.html.twig', array('reviews' => $reviews));
    }


    /**
     * Delete Review
     * @param Request $request
     * @param Review $review
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Review $review) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            $em->remove($review);
            $em->flush();

            $this->get('AccommodationService')->calculateReviewRate($review);
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('admin_reviews'));
    }

    /**
     * Change Review status
     * @param Request $request
     * @param Review $review
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeStatusAction(Request $request, Review $review) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            $status = ($review->getStatus()) ? 0 : 1;
            $review->setStatus($status);

            $em->persist($review);
            $em->flush();

            $this->get('AccommodationService')->calculateReviewRate($review);
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('change_status_success'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('admin_reviews'));
    }

}