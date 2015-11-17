<?php

namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class StatService {


    protected $em;
    protected $session;

    function __construct(EntityManager $em, Session $session) {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * Get booking result - host
     * @param $host
     * @param $status
     * @return int
     */
    public function getHostBookingNum($host, $status) {
        $bookings = $this->em->getRepository('AppBundle:Booking')->getBookingsHost($host, $status);
        return count($bookings);
    }

    public function getGuestBookingNum($guest, $status) {
        $bookings = $this->em->getRepository('AppBundle:Booking')->getBookingsGuest($guest, $status);
        return count($bookings);
    }

    public function getWishlistNum($guest, $status) {
        $bookings = $this->em->getRepository('AppBundle:Booking')->getBookingsGuest($guest, $status);
        return count($bookings);
    }

    public function getAccommodationNumHost($host) {
        $a = $this->em->getRepository('AppBundle:Accommodation')->getAccommodationsUser($host->getId());
        return count($a);
    }

    public function getUnitNumHost($host) {
        $unit = $this->em->getRepository('AppBundle:Unit')->getUnitNumHost($host);
        return $unit;
    }


    public function getFeesNum() {
        return $this->em->getRepository('AppBundle:AccommodationFee')->getActiveFeeNum();
    }

    public function getCommisionNum() {
        return $this->em->getRepository('AppBundle:AccommodationFee')->getCommisionNum();
    }

    public function getUsersNum($status) {
        return $this->em->getRepository('UserBundle:User')->getAllNum($status);
    }

    public function getAccommodationsNum() {
        return $this->em->getRepository('AppBundle:Accommodation')->getAccommdationNum();
    }

}