<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Form\BookingNewType;
use AppBundle\Form\BookingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swift_SmtpTransport;
use Swift_Mailer;


class BookingController extends Controller {

    ##############
    ### FUTURE ###
    ##############

    /**
     * User bookings
     * @return Response
     */
    public function indexAction() {
        # If user == admin he has no bookings
        if($this->get('security.context')->isGranted('ROLE_ADMIN'))
            return $this->redirect($this->generateUrl('admin_home'));

        # Diferent bookings for host & tourist
        if($this->get('security.context')->isGranted('ROLE_HOST')) {
            return $this->hostBookings();
        } else {
            return $this->guestBookings();
        }
    }

    /**
     * Host bookings
     * @return Response
     */
    private function hostBookings() {
        $now = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $bookings = $em->getRepository('AppBundle:Booking')->getBookingsHost($user, 1, null, $now);

        return $this->render('AppBundle:Booking:bookingsHost.html.twig', array(
            'host'      => $user,
            'bookings'  => $bookings
        ));
    }

    /**
     * Guest bookings
     * @return Response
     */
    private function guestBookings() {
        $now = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $bookings = $em->getRepository('AppBundle:Booking')->getBookingsGuest($user, 1, $now);

        return $this->render('AppBundle:Booking:bookingsGuest.html.twig', array(
            'user'      => $user,
            'bookings'  => $bookings
        ));
    }

    ###########
    ### OLD ###
    ###########

    public function oldAction() {
        # If user == admin he has no bookings
        if($this->get('security.context')->isGranted('ROLE_ADMIN'))
            return $this->redirect($this->generateUrl('admin_home'));

        # Diferent bookings for host & tourist
        if($this->get('security.context')->isGranted('ROLE_HOST')) {
            return $this->hostOldBookings();
        } else {
            return $this->guestOldBookings();
        }
    }

    public function guestOldBookings() {
        $now = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $bookings = $em->getRepository('AppBundle:Booking')->getOldBookingsGuest($user, $now);

        return $this->render('AppBundle:Booking:oldBookingsGuest.html.twig', array(
            'user'      => $user,
            'bookings'  => $bookings
        ));
    }

    public function hostOldBookings() {
        $now = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $bookings = $em->getRepository('AppBundle:Booking')->getOldBookingsHost($user, $now);

        return $this->render('AppBundle:Booking:oldBookingsHost.html.twig', array(
            'user'      => $user,
            'bookings'  => $bookings
        ));
    }


    ###############
    ### PENDING ###
    ###############

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function pendingAction() {
        # If user == admin he has no bookings
        if($this->get('security.context')->isGranted('ROLE_ADMIN'))
            return $this->redirect($this->generateUrl('admin_home'));

        # Diferent bookings for host & tourist
        if($this->get('security.context')->isGranted('ROLE_HOST')) {
            return $this->hostPendingBookings();
        } else {
            return $this->guestPendingBookings();
        }
    }

    /**
     * Host bookings
     * @return Response
     */
    public function hostPendingBookings() {
        $em = $this->getDoctrine()->getManager();
        $bookingRepo = $em->getRepository('AppBundle:Booking');
        $user = $this->getUser();

        # Set all pending bookings as NOT new
//        if($res = $bookingRepo->updatePendingBookingNotNew($user)) {
//            return $res;
//        } else {
//            return $res;
//        }

        $bookings = $bookingRepo->getBookingsHost($user, 0);
        foreach($bookings as $booking) {
            $bookingRepo->updatePendingBookingNotNew($booking);
        }

        return $this->render('AppBundle:Booking:pendingBookingsHost.html.twig', array(
            'host'      => $user,
            'bookings'  => $bookings
        ));
    }

    /**
     * Guest bookings
     * @return Response
     */
    public function guestPendingBookings() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $bookings = $em->getRepository('AppBundle:Booking')->getBookingsGuest($user, 0);

        return $this->render('AppBundle:Booking:pendingBookingsGuest.html.twig', array(
            'user'      => $user,
            'bookings'  => $bookings
        ));
    }


    ############
    ### MISC ###
    ############

    /**
     * Booking - change status (confirm)
     * @param Request $request
     * @param Booking $booking
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request, Booking $booking) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        if(!$this->get('PermissionsService')->bookingConfirmAllowed($booking, $this->getUser()))
            return $this->redirect($this->generateUrl('user_403'));

        try {
            $this->bookingConfirmEmail($this->getUser(), $booking, 'host');
            $this->bookingConfirmEmail($booking->getUser(), $booking, 'guest');

            $booking->setStatus(1);
            $em->persist($booking);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('confirm_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('app_bookings_pending'));
    }

    /**
     * Delete bookings
     * @param Request $request
     * @param Booking $booking
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Booking $booking) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        if($this->get('security.context')->isGranted('ROLE_GUEST')) {
            $role = 'guest';
        } elseif($this->get('security.context')->isGranted('ROLE_HOST')) {
            $role = 'host';
        }

        if(!$this->get('PermissionsService')->bookingDeleteAllowed($this->getUser(), $role, $booking))
            return $this->redirect($this->generateUrl('user_403'));

        try {
            $toDate = $booking->getToDate()->format('Y-m-d');
            $n = new \DateTime();
            $now = $n->format('Y-m-d');

            if($toDate > $now ) {
                $this->bookingDeleteEmail($booking->getUnit()->getAccommodation()->getUser(), $booking, 'host');
                $this->bookingDeleteEmail($booking->getUser(), $booking, 'guest');
            }

            $em->remove($booking);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('app_bookings'));
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $booking = new Booking();

        $units = $em->getRepository('AppBundle:Unit')->getUnitsHost($this->getUser());

        $form = $this->createForm(new BookingNewType($units), $booking);

        $form->handleRequest($request);
        if($form->isValid()) {
            $booking->setStatus(1);
            $booking->setUser($this->getUser());
            $booking->setNew(0);
            $em->persist($booking);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));
            return $this->redirect($this->generateUrl('app_bookings'));
        }

        return $this->render('AppBundle:Booking:new.html.twig', array(
            'form'          => $form->createView()
        ));
    }



    private function bookingConfirmEmail($user, $booking, $role) {
        try {
            if($role == 'host') {
                $subject = $this->get('translator')->trans('Best-Destination.com – '.$booking->getId().' Reservation CONFIRMED for accommodation unit ' . $booking->getUnit()->getName());
            } else {
                $subject = $this->get('translator')->trans('Best-destination.com – '.$booking->getUnit()->getAccommodation()->getName().' has been confirmed');
            }

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->get('emailService')->getSiteEmail())
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                    'AppBundle:Emails:booking_confirm.html.twig',
                    array(
                        'user' => $user,
                        'role' => $role,
                        'booking' => $booking
                    )
                ),
                    'text/html');

            $transport = Swift_SmtpTransport::newInstance('localhost', 25);
            $mailer = Swift_Mailer::newInstance($transport);

            $mailer->send($message);
            return true;

        } catch(ExportException $e) {
            return false;
        }
    }


    private function bookingDeleteEmail($user, $booking, $role) {
        try {
            if($role == 'host') {
                $subject = $this->get('translator')->trans('Best-Destination.com – '.$booking->getId().' CANCELATION for accommodation unit ' . $booking->getUnit()->getName());
            } else {
                $subject = $this->get('translator')->trans('Best-destination.com – '.$booking->getUnit()->getAccommodation()->getName().' has been canceled');
            }

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->get('emailService')->getSiteEmail())
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                    'AppBundle:Emails:booking_delete.html.twig',
                    array(
                        'user' => $user,
                        'role' => ($role == 'host') ? 'guest' : 'host',
                        'booking' => $booking
                    )
                ),
                    'text/html');

            $transport = Swift_SmtpTransport::newInstance('localhost', 25);
            $mailer = Swift_Mailer::newInstance($transport);

            $mailer->send($message);
            return true;

        } catch(ExportException $e) {
            return false;
        }
    }

}