<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="accommodation_distance")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AccommodationDistanceRepository")
 */
class AccommodationDistance {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Accommodation", mappedBy="distance")
     */
    private $accommodation;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $restaurant;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $farmacy;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $nightClub;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $shoppingMall;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $postOffice;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $bus;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $train;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $wellness;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $airport;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $museum;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $sportsCenter;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $casino;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $store;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $golf;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $doctor;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $cinema;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $bank;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $atm;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $gasStation;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $park;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $library;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $fitness;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $nationalPark;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $amusementPark;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $center;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $beach;
    /**
     * @ORM\Column(type="integer", length=6, nullable=true)
     */
    private $sea;

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set restaurant
     * @param integer $restaurant
     * @return AccommodationDistance
     */
    public function setRestaurant($restaurant) {
        $this->restaurant = $restaurant;
        return $this;
    }

    /**
     * Get restaurant
     * @return integer
     */
    public function getRestaurant() {
        return $this->restaurant;
    }

    /**
     * Set farmacy
     * @param integer $farmacy
     * @return AccommodationDistance
     */
    public function setFarmacy($farmacy) {
        $this->farmacy = $farmacy;
        return $this;
    }

    /**
     * Get farmacy
     * @return integer
     */
    public function getFarmacy() {
        return $this->farmacy;
    }

    /**
     * Set nightClub
     * @param integer $nightClub
     * @return AccommodationDistance
     */
    public function setNightClub($nightClub) {
        $this->nightClub = $nightClub;
        return $this;
    }

    /**
     * Get nightClub
     * @return integer
     */
    public function getNightClub() {
        return $this->nightClub;
    }

    /**
     * Set postOffice
     * @param integer $postOffice
     * @return AccommodationDistance
     */
    public function setPostOffice($postOffice) {
        $this->postOffice = $postOffice;
        return $this;
    }

    /**
     * Get postOffice
     * @return integer
     */
    public function getPostOffice() {
        return $this->postOffice;
    }

    /**
     * Set bus
     * @param integer $bus
     * @return AccommodationDistance
     */
    public function setBus($bus) {
        $this->bus = $bus;
        return $this;
    }

    /**
     * Get bus
     * @return integer
     */
    public function getBus() {
        return $this->bus;
    }

    /**
     * Set train
     * @param integer $train
     * @return AccommodationDistance
     */
    public function setTrain($train) {
        $this->train = $train;
        return $this;
    }

    /**
     * Get train
     * @return integer
     */
    public function getTrain() {
        return $this->train;
    }

    /**
     * Set wellness
     * @param integer $wellness
     * @return AccommodationDistance
     */
    public function setWellness($wellness) {
        $this->wellness = $wellness;
        return $this;
    }

    /**
     * Get wellness
     * @return integer
     */
    public function getWellness() {
        return $this->wellness;
    }

    /**
     * Set airport
     * @param integer $airport
     * @return AccommodationDistance
     */
    public function setAirport($airport) {
        $this->airport = $airport;
        return $this;
    }

    /**
     * Get airport
     * @return integer
     */
    public function getAirport() {
        return $this->airport;
    }

    /**
     * Set museum
     * @param integer $museum
     * @return AccommodationDistance
     */
    public function setMuseum($museum) {
        $this->museum = $museum;
        return $this;
    }

    /**
     * Get museum
     * @return integer
     */
    public function getMuseum() {
        return $this->museum;
    }

    /**
     * Set sportsCenter
     * @param integer $sportsCenter
     * @return AccommodationDistance
     */
    public function setSportsCenter($sportsCenter) {
        $this->sportsCenter = $sportsCenter;
        return $this;
    }

    /**
     * Get sportsCenter
     * @return integer
     */
    public function getSportsCenter() {
        return $this->sportsCenter;
    }

    /**
     * Set casino
     * @param integer $casino
     * @return AccommodationDistance
     */
    public function setCasino($casino) {
        $this->casino = $casino;
        return $this;
    }

    /**
     * Get casino
     * @return integer
     */
    public function getCasino() {
        return $this->casino;
    }

    /**
     * Set store
     * @param integer $store
     * @return AccommodationDistance
     */
    public function setStore($store) {
        $this->store = $store;
        return $this;
    }

    /**
     * Get store
     * @return integer
     */
    public function getStore() {
        return $this->store;
    }

    /**
     * Set golf
     * @param integer $golf
     * @return AccommodationDistance
     */
    public function setGolf($golf) {
        $this->golf = $golf;
        return $this;
    }

    /**
     * Get golf
     * @return integer
     */
    public function getGolf() {
        return $this->golf;
    }

    /**
     * Set doctor
     * @param integer $doctor
     * @return AccommodationDistance
     */
    public function setDoctor($doctor) {
        $this->doctor = $doctor;
        return $this;
    }

    /**
     * Get doctor
     * @return integer
     */
    public function getDoctor() {
        return $this->doctor;
    }

    /**
     * Set cinema
     * @param integer $cinema
     * @return AccommodationDistance
     */
    public function setCinema($cinema) {
        $this->cinema = $cinema;
        return $this;
    }

    /**
     * Get cinema
     * @return integer
     */
    public function getCinema() {
        return $this->cinema;
    }

    /**
     * Set bank
     * @param integer $bank
     * @return AccommodationDistance
     */
    public function setBank($bank) {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Get bank
     * @return integer
     */
    public function getBank() {
        return $this->bank;
    }

    /**
     * Set atm
     * @param integer $atm
     * @return AccommodationDistance
     */
    public function setAtm($atm) {
        $this->atm = $atm;
        return $this;
    }

    /**
     * Get atm
     * @return integer
     */
    public function getAtm() {
        return $this->atm;
    }

    /**
     * Set gasStation
     * @param integer $gasStation
     * @return AccommodationDistance
     */
    public function setGasStation($gasStation) {
        $this->gasStation = $gasStation;
        return $this;
    }

    /**
     * Get gasStation
     * @return integer
     */
    public function getGasStation() {
        return $this->gasStation;
    }

    /**
     * Set park
     * @param integer $park
     * @return AccommodationDistance
     */
    public function setPark($park) {
        $this->park = $park;
        return $this;
    }

    /**
     * Get park
     * @return integer
     */
    public function getPark() {
        return $this->park;
    }

    /**
     * Set library
     * @param integer $library
     * @return AccommodationDistance
     */
    public function setLibrary($library) {
        $this->library = $library;
        return $this;
    }

    /**
     * Get library
     * @return integer
     */
    public function getLibrary() {
        return $this->library;
    }

    /**
     * Set nationalPark
     * @param integer $nationalPark
     * @return AccommodationDistance
     */
    public function setNationalPark($nationalPark) {
        $this->nationalPark = $nationalPark;
        return $this;
    }

    /**
     * Get nationalPark
     * @return integer
     */
    public function getNationalPark() {
        return $this->nationalPark;
    }

    /**
     * Set amusementPark
     * @param integer $amusementPark
     * @return AccommodationDistance
     */
    public function setAmusementPark($amusementPark) {
        $this->amusementPark = $amusementPark;
        return $this;
    }

    /**
     * Get amusementPark
     * @return integer
     */
    public function getAmusementPark() {
        return $this->amusementPark;
    }

    /**
     * Set accommodation
     * @param \AppBundle\Entity\Accommodation $accommodation
     * @return AccommodationDistance
     */
    public function setAccommodation(\AppBundle\Entity\Accommodation $accommodation = null) {
        $this->accommodation = $accommodation;
        return $this;
    }

    /**
     * Get accommodation
     * @return \AppBundle\Entity\Accommodation
     */
    public function getAccommodation() {
        return $this->accommodation;
    }

    /**
     * Set shoppingMall
     * @param integer $shoppingMall
     * @return AccommodationDistance
     */
    public function setShoppingMall($shoppingMall) {
        $this->shoppingMall = $shoppingMall;
        return $this;
    }

    /**
     * Get shoppingMall
     * @return integer
     */
    public function getShoppingMall() {
        return $this->shoppingMall;
    }

    /**
     * Set fitness
     * @param integer $fitness
     * @return AccommodationDistance
     */
    public function setFitness($fitness) {
        $this->fitness = $fitness;
        return $this;
    }

    /**
     * Get fitness
     * @return integer
     */
    public function getFitness() {
        return $this->fitness;
    }

    /**
     * Set center
     * @param integer $center
     * @return AccommodationDistance
     */
    public function setCenter($center) {
        $this->center = $center;

        return $this;
    }

    /**
     * Get center
     * @return integer
     */
    public function getCenter() {
        return $this->center;
    }

    /**
     * Set beach
     * @param integer $beach
     * @return AccommodationDistance
     */
    public function setBeach($beach) {
        $this->beach = $beach;

        return $this;
    }

    /**
     * Get beach
     * @return integer
     */
    public function getBeach() {
        return $this->beach;
    }

    /**
     * Set sea
     * @param integer $sea
     * @return AccommodationDistance
     */
    public function setSea($sea) {
        $this->sea = $sea;

        return $this;
    }

    /**
     * Get sea
     * @return integer
     */
    public function getSea() {
        return $this->sea;
    }
}
