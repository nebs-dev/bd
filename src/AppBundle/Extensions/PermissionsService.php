<?php

namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use DateTime;

class PermissionsService {


    protected $em;
    protected $session;
    protected $securityContext;

    function __construct(EntityManager $em, Session $session, SecurityContext $securityContext) {
        $this->em = $em;
        $this->session = $session;
        $this->securityContext = $securityContext;
    }

    /**
     * Check if user is accommodations owner
     * @param $accommodation
     * @return bool
     */
    public function isOwner($user, $accommodation) {
        return $accommodation->getUser() == $user ? true : false;
    }

    /**
     * Check if conversation is permitted
     * @param $user
     * @param $sender
     * @return bool
     */
    public function conversationAllowed($user, $sender) {
        if($this->securityContext->isGranted('ROLE_GUEST')) return true;

        if(count($this->em->getRepository('AppBundle:Booking')->getHostGuestBookings($user, $sender))) {
            return true;
        } elseif(count($this->em->getRepository('UserBundle:Message')->getConversation($user, $sender))) {
            return true;
        }

        return false;
    }

    /**
     * Check if user is permitted to delete booking
     * @param $user
     * @param $role
     * @param $booking
     * @return bool
     */
    public function bookingDeleteAllowed($user, $role, $booking) {
        if($role == 'guest') {
            if($user == $booking->getUser()) return true;
        } else if($role == 'host') {
            if($user == $booking->getUnit()->getAccommodation()->getUser()) return true;
        } else {
            return false;
        }

        return false;
    }

    /**
     * Check if booking confirmation is allowed
     * @param $booking
     * @param $host
     * @return bool
     */
    public function bookingConfirmAllowed($booking, $host) {
        if($this->securityContext->isGranted('ROLE_GUEST')) return false;

        if($host == $booking->getUnit()->getAccommodation()->getUser()) return true;

        return false;
    }

    /**
     * Check if user is allowed to review accommodation
     * @param $accommodation
     * @param $user
     * @return bool
     */
    public function reviewAllowed($accommodation, $user) {
        $bookingRepo = $this->em->getRepository('AppBundle:Booking');
        $reviewRepo = $this->em->getRepository('AppBundle:Review');
        $now = new DateTime();

        # If user didn't reviewed and had booked this accommodation
        if($reviewRepo->userReviewed($accommodation, $user) == 0 && $bookingRepo->getUserBooked($accommodation, $user, $now) > 0)
            return true;

        return false;
    }

}