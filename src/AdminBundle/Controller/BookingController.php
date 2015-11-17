<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Accommodation;
use AppBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class BookingController extends Controller {

    /**
     * Finished bookings
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $bookings = $em->getRepository('AppBundle:Booking')->getAll(1);

        return $this->render('AdminBundle:Booking:bookings.html.twig', array(
            'bookings'  => $bookings
        ));
    }

    /**
     * Pending bookings
     * @return Response
     */
    public function pendingAction() {
        $em = $this->getDoctrine()->getManager();
        $bookings = $em->getRepository('AppBundle:Booking')->getAll(0);

        return $this->render('AdminBundle:Booking:pendingBookings.html.twig', array(
            'bookings'  => $bookings
        ));
    }


    /**
     * All not payed commisions
     * @return Response
     */
    public function commisionPendingAction() {
        $em = $this->getDoctrine()->getManager();
        $bookings = $em->getRepository('AppBundle:Booking')->getCommisionNotPayed();

        return $this->render('AdminBundle:Booking:bookingsCommisions.html.twig', array(
            'bookings'  => $bookings
        ));
    }

    /**
     * Get all commisions for Accommodation
     * @param Accommodation $accommodation
     * @return Response
     */
    public function commisionsAccommodationAction(Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $bookings = $em->getRepository('AppBundle:Booking')->getCommisionsAccommodation($accommodation);

        return $this->render('AdminBundle:Booking:commisionsAccommodation.html.twig', array(
            'bookings'  => $bookings,
            'accommodation' => $accommodation
        ));
    }


    /**
     * Change status of booking with commision
     * @param Request $request
     * @param Booking $booking
     * @param $status
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function bookingCommisionStatusAction(Request $request, Booking $booking, $status) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $accommodation = $booking->getUnit()->getAccommodation();

        $status = ($status == 'null') ? NULL : $status;

        try {
            $booking->setCommision($status);
            $em->persist($booking);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('change_status_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('change_status_error'));
        }

        return $this->redirect($this->generateUrl('admin_bookings_commision_accommodation', array(
            'id' => $accommodation->getId()
        )));
    }

    /**
     * Send bill to all new commisions for this accommodation
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendBillAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {

            $bookings = $em->getRepository('AppBundle:Booking')->getNewCommisionsAccommodation($accommodation);
            foreach($bookings as $booking) {
                $booking->setCommision(1);
                $em->persist($booking);
                $em->flush();
            }

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('bill_sent'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('admin_bookings_commision_accommodation', array(
            'id' => $accommodation->getId()
        )));
    }

}