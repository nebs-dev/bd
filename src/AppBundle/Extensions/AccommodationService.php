<?php

namespace AppBundle\Extensions;

use AppBundle\Entity\Accommodation;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class AccommodationService {

    protected $em;
    protected $session;

    function __construct(EntityManager $em, Session $session) {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * Get the lowest price for accommodation
     * @param Accommodation $accommodation
     * @return mixed
     */
    public function getLowPrice(Accommodation $accommodation) {
        return $this->em->getRepository('AppBundle:Unit')->getLowPrice($accommodation);
    }

    /**
     * Return current pagination page
     * @param $page
     * @param $numPages
     * @return int
     */
    public function getCurrentPaginationPage($page, $numPages) {
        switch ($page) {
            case 'first':
                return 1;
                break;
            case 'last';
                return ($numPages) ? $numPages : $numPages + 1;
                break;
            default:
                return $page;
                break;
        }
    }

    /**
     * Check if its permitted to add more units
     * @param $accommodation
     * @return bool
     */
    public function moreUnitAllowed($accommodation) {
        $activeFee = $this->em->getRepository('AppBundle:AccommodationFee')->getActiveFee($accommodation);
        if(!$activeFee->getType()->getPayment()) return true;

        if(!is_null($activeFee)) {
            if($activeFee->getType()->getUnitNumber() > count($accommodation->getUnits())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if its permitted to add more units
     * @param $accommodation
     * @return bool
     */
    public function moreUnitAllowedSteps($accommodation) {
        $lastFee = $this->em->getRepository('AppBundle:AccommodationFee')->getLastFee($accommodation);

        if(!is_null($lastFee) && $lastFee->getType()->getPayment()) {
            if($lastFee->getType()->getUnitNumber() > count($accommodation->getUnits())) {
                return true;
            }
        }

        return false;
    }

    public function getJSONMap($accommodations) {
        $jsonArray = array();
        foreach($accommodations as $accommodation) {
            $a['title'] = $accommodation->getName();
            $a['lng'] = $accommodation->getX();
            $a['lat'] = $accommodation->getY();

            if($accommodation->getDescription()) {
                $a['description'] = $accommodation->getDescription()->getEn();
            } else {
                $a['description'] = '';
            }

            array_push($jsonArray, $a);
        }

        return json_encode($jsonArray);
    }


    /**
     * Return single price extra for Unit
     * @param $priceExtraName
     * @param $unit
     * @return mixed
     */
    public function getUnitPriceExtra($priceExtraName, $unit) {
        $response = $this->em->getRepository('AppBundle:UnitPriceExtra')->getUnitPriceExtraByName($priceExtraName, $unit);
        return $response;
    }


    /**
     * Calculate total accommodation after change review status
     * @param $review
     */
    public function calculateReviewRate($review) {
        $accommodation = $review->getAccommodation();
        $data = $this->em->getRepository('AppBundle:Review')->getAverageRate($accommodation);

        $accommodation->setRate($data['rate_avg']);
        $this->em->persist($accommodation);
        $this->em->flush();
    }


    /**
     * Get icons name for accommodation search
     * @param $accommodation
     * @return array
     */
    public function getIcons($accommodation) {
        $aRepo = $this->em->getRepository('AppBundle:Accommodation');
        $icons = array();

        # wifi
        if(!is_null($accommodation->getWifi()) && $accommodation->getWifi() == 0) {
            $icons[] = 'wifi';
            if($this->iconsNum($icons)) return $icons;
        }
        # parking
        if($aRepo->getAvalaiblePriceExtra($accommodation, 'parking')) {
            $icons[] = 'parking';
            if($this->iconsNum($icons)) return $icons;
        }
        # pets
        if($aRepo->getAvalaiblePriceExtra($accommodation, 'pets')) {
            $icons[] = 'pets';
            if($this->iconsNum($icons)) return $icons;
        }
        # breakfast
        if($aRepo->getAvalaiblePriceExtra($accommodation, 'breakfast')) {
            $icons[] = 'breakfast';
            if($this->iconsNum($icons)) return $icons;
        }
        # half_board
        if($aRepo->getAvalaiblePriceExtra($accommodation, 'half_board')) {
            $icons[] = 'half_board';
            if($this->iconsNum($icons)) return $icons;
        }
        # all_inclusive
        if($aRepo->getAvalaiblePriceExtra($accommodation, 'all_inclusive')) {
            $icons[] = 'all_inclusive';
            if($this->iconsNum($icons)) return $icons;
        }
        # pool
        if($aRepo->getAvalaibleFacilities($accommodation, 'pool')) {
            $icons[] = 'pool';
            if($this->iconsNum($icons)) return $icons;
        }
        # sandy_beach
        if($aRepo->getAvalaibleBeaches($accommodation, 'sandy_beach')) {
            $icons[] = 'sandy_beach';
            if($this->iconsNum($icons)) return $icons;
        }

        return $icons;
    }

    /**
     * number of accommodation icons
     * @param $icons
     * @return bool
     */
    private function iconsNum($icons) {
        if(count($icons) >= 3) return true;
    }

    /**
     * Get accommodations last fee
     * @param $accommodation
     * @return null
     */
    public function getLastFee($accommodation) {
        $aRepo = $this->em->getRepository('AppBundle:Accommodation');
        $lastFee = null;
        foreach($accommodation->getFees() as $fee) {
            $lastFee = $fee;
        }
        return $lastFee;
    }

}