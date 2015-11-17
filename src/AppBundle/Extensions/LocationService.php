<?php

namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class LocationService {

    protected $em;
    protected $session;

    function __construct(EntityManager $em, Session $session) {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * Get location type
     * @param $location
     * @return string
     */
    public function getLocationType($location) {
        $country    = $this->em->getRepository('AppBundle:Country')->getByEn($location);
        $region     = $this->em->getRepository('AppBundle:Region')->getByEn($location);
        $subregion  = $this->em->getRepository('AppBundle:Subregion')->getByEn($location);
        $city       = $this->em->getRepository('AppBundle:City')->getByEn($location);

        if($city) return 'city';
        if($subregion) return 'subregion';
        if($region) return 'region';
        if($country) return 'country';

        return false;
    }

    /**
     * Get location object by type && name
     * @param $locationType
     * @param $location
     * @return bool
     */
    public function getLocationObject($locationType, $location) {
        switch($locationType) {
            case 'city':
                $locationObj = $this->em->getRepository('AppBundle:City')->getByEn($location);
                break;
            case 'subregion':
                $locationObj = $this->em->getRepository('AppBundle:Subregion')->getByEn($location);
                break;
            case 'region':
                $locationObj = $this->em->getRepository('AppBundle:Region')->getByEn($location);
                break;
            case 'country':
                $locationObj = $this->em->getRepository('AppBundle:Country')->getByEn($location);
                break;
            default:
                $locationObj = NULL;
                break;
        }

        return $locationObj;
    }

    /**
     * Get Search header image
     * @param $locationType
     * @param $locationObject
     * @return mixed
     */
    public function getLocationPhoto($locationType, $locationObject) {
        switch($locationType) {
            case 'city':
                if($locationObject->getWebPath()) {
                    $photo = $locationObject->getWebPath();
                } elseif($locationObject->getSubregion() && $locationObject->getSubregion()->getWebPath()) {
                    $photo = $locationObject->getSubregion()->getWebPath();
                } elseif($locationObject->getRegion()->getWebPath()) {
                    $photo = $locationObject->getRegion()->getWebPath();
                } else {
                    $photo = $locationObject->getRegion()->getCountry()->getWebPath();
                }

                break;
            case 'subregion':
                if($locationObject->getWebPath()) {
                    $photo = $locationObject->getWebPath();
                } elseif($locationObject->getRegion()->getWebPath()) {
                    $photo = $locationObject->getRegion()->getWebPath();
                } else {
                    $photo = $locationObject->getRegion()->getCountry()->getWebPath();
                }

                break;
            case 'region':
                if($locationObject->getWebPath()) {
                    $photo = $locationObject->getWebPath();
                } else {
                    $photo = $locationObject->getRegion()->getCountry()->getWebPath();
                }

                break;
            case 'country':
                $photo = $locationObject->getWebPath();
                break;
            default:
                $photo = false;
                break;
        }

        return $photo;
    }


}