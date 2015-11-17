<?php

namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class BookingService {

    protected $em;
    protected $session;

    function __construct(EntityManager $em, Session $session) {
        $this->em = $em;
        $this->session = $session;
    }


    public function pendingNum($host) {
        return count($this->em->getRepository('AppBundle:Booking')->getBookingsHost($host, 0, 1));
    }


    public function bookingAllowed($booking) {
        $numBookings = $this->em->getRepository('AppBundle:Booking')->getBookedNum($booking);
        return ($numBookings == 0) ? true : false;
    }


    /**
     * Booking dates for single unit calendar
     * @param $unit
     * @return array
     */
    public function getAllBookingDatesUnit($unit) {
        $bookings = $this->em->getRepository('AppBundle:Booking')->getActiveBookings($unit);

        $allDates = array();
        foreach($bookings as $booking) {
            $allDates = array_merge($allDates, $this->date_range($booking->getFromDate()->format('Y-m-d'), $booking->getToDate()->format('Y-m-d')));
        }

        return json_encode($allDates);
    }


    /**
     * Creating date collection between two dates
     *
     * <code>
     * <?php
     * # Example 1
     * date_range("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");
     *
     * # Example 2. you can use even time
     * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
     * </code>
     *
     * @param string since any date, time or datetime format
     * @param string until any date, time or datetime format
     * @param string step
     * @param string date of output format
     * @return array
     */
    private function date_range($first, $last, $step = '+1 day', $output_format = 'd.m.Y.' ) {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

}