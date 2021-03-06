<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UnitPeriodRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UnitPeriodRepository extends EntityRepository {

    public function getAll($unitId) {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.unit = :unitId')
            ->setParameter('unitId', $unitId);

        return $qb->getQuery()->getResult();
    }


    public function getCheckoutData($unit, $checkIn, $checkOut) {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('(:checkIn BETWEEN p.fromDate AND p.toDate) OR (:checkOut BETWEEN p.fromDate AND p.toDate) OR (:checkIn < p.fromDate AND :checkOut > p.toDate)')
            ->andWhere('p.unit = :unit')
            ->setParameter('checkIn', $checkIn)
            ->setParameter('checkOut', $checkOut)
            ->setParameter('unit', $unit);

        $periods = $qb->getQuery()->getResult();
        $data = $this->calculatePrices($periods, $unit, $checkIn, $checkOut);

        return $data;
    }


    private function calculatePrices($periods, $unit, $checkIn, $checkOut) {
//        echo 'Nights: ' . $checkIn->diff($checkOut)->format('%d%') . '<br/><br/>';

        $response = array();

        $basicPrice = $unit->getBasicPrice();
        $fullPrice = 0;
        $basicNights = $checkIn->diff($checkOut)->format('%d%');

        if(count($periods) > 0) {
            foreach ($periods as $period) {
                # If full period is between booking dates
                if ($checkIn < $period->getFromDate() && $checkOut > $period->getToDate()) {
                    $interval = $period->getFromDate()->diff($period->getToDate())->format('%d%');

                    $nightsToPay = $interval + 1;
                    $basicNights = $basicNights - $nightsToPay;

                    $periodPrice = $nightsToPay * $period->getAmount();
                    $fullPrice += $periodPrice + ($basicPrice * $nightsToPay);

                    //                echo 'Period Nights Pay: ' . $nightsToPay . '<br/>';
                    //                echo 'Period Nights Total: ' . $interval . '<br/>';
                    //                echo 'Single price: ' . $period->getAmount() . '<br/>';
                    //                echo 'Period Price: ' . $periodPrice . '<br/>';
                    //                echo 'Full Price: ' . $fullPrice . '<br/><br/>';

                    $singlePeriod = array(
                        'from' => $period->getFromDate(),
                        'to' => $period->getToDate(),
                        'pricePD' => $period->getAmount(),
                        'nights' => $nightsToPay,
                        'total' => $periodPrice + ($basicPrice * $nightsToPay)
                    );

                    $response['periods'][] = $singlePeriod;
                }

                # If only Check In is in period
                if (($checkIn >= $period->getFromDate() && $checkIn <= $period->getToDate()) && $checkOut > $period->getToDate()) {
                    $interval = $period->getFromDate()->diff($period->getToDate())->format('%d%');

                    //                $additionalNight = ($checkIn == $period->getFromDate()) ? 1 : 0;
                    $nightsToPay = $checkIn->diff($period->getToDate())->format('%d%') + 1;
                    $basicNights = $basicNights - $nightsToPay;

                    $periodPrice = $nightsToPay * $period->getAmount();
                    $fullPrice += $periodPrice + ($basicPrice * $nightsToPay);

                    //                echo 'Period Nights Pay: ' . $nightsToPay . '<br/>';
                    //                echo 'Period Nights Total: ' . $interval . '<br/>';
                    //                echo 'Single price: ' . $period->getAmount() . '<br/>';
                    //                echo 'Period Price: ' . $periodPrice . '<br/>';
                    //                echo 'Full Price: ' . $fullPrice . '<br/><br/>';

                    $singlePeriod = array(
                        'from' => $checkIn,
                        'to' => $period->getToDate(),
                        'pricePD' => $period->getAmount(),
                        'nights' => $nightsToPay,
                        'total' => $periodPrice + ($basicPrice * $nightsToPay)
                    );

                    $response['periods'][] = $singlePeriod;
                }

                # If only Check Out is in period
                if ($checkIn < $period->getFromDate() && ($checkOut >= $period->getFromDate() && $checkOut <= $period->getToDate())) {
                    $interval = $period->getFromDate()->diff($period->getToDate())->format('%d%');

                    //                $additionalNight = ($checkOut == $period->getFromDate()) ? 1 : 0;
                    $nightsToPay = $checkOut->diff($period->getFromDate())->format('%d%');
                    $basicNights = $basicNights - $nightsToPay;

                    $periodPrice = $nightsToPay * $period->getAmount();
                    $fullPrice += $periodPrice + ($basicPrice * $nightsToPay);

                    //                echo 'Period Nights Pay: ' . $nightsToPay . '<br/>';
                    //                echo 'Period Nights Total: ' . $interval . '<br/>';
                    //                echo 'Single price: ' . $period->getAmount() . '<br/>';
                    //                echo 'Period Price: ' . $periodPrice . '<br/>';
                    //                echo 'Full Price: ' . $fullPrice . '<br/><br/>';

                    $singlePeriod = array(
                        'from' => $period->getFromDate(),
                        'to' => $checkOut,
                        'pricePD' => $period->getAmount(),
                        'nights' => $nightsToPay,
                        'total' => $periodPrice + ($basicPrice * $nightsToPay)
                    );

                    $response['periods'][] = $singlePeriod;
                }

                # If check In & Out are in One Period
                if ($checkIn >= $period->getFromDate() && $checkOut <= $period->getToDate()) {
                    $interval = $period->getFromDate()->diff($period->getToDate())->format('%d%');

                    $nightsToPay = $checkIn->diff($checkOut)->format('%d%');
                    $basicNights = $basicNights - $nightsToPay;

                    $periodPrice = $interval * $period->getAmount();
                    $fullPrice += $periodPrice + ($basicPrice * $nightsToPay);

                    //                echo 'Period Nights Pay: ' . $nightsToPay . '<br/>';
                    //                echo 'Period Nights Total: ' . $interval . '<br/>';
                    //                echo 'Single price: ' . $period->getAmount() . '<br/>';
                    //                echo 'Period Price: ' . $periodPrice . '<br/>';
                    //                echo 'Full Price: ' . $fullPrice . '<br/><br/>';

                    $singlePeriod = array(
                        'from' => $checkIn,
                        'to' => $checkOut,
                        'pricePD' => $period->getAmount(),
                        'nights' => $nightsToPay,
                        'total' => $periodPrice + ($basicPrice * $nightsToPay)
                    );

                    $response['periods'][] = $singlePeriod;
                }
            }

            $fullPrice = $fullPrice + ($basicNights * $basicPrice);

        } else {
            $fullPrice = $basicPrice * $checkIn->diff($checkOut)->format('%d%');
        }

        $response['fullPrice'] = $fullPrice;
        $response['checkIn'] = $checkIn;
        $response['checkOut'] = $checkOut;
        $response['unit'] = $unit;
        $response['nights'] = $checkIn->diff($checkOut)->format('%d%');

        return $response;
    }
}
