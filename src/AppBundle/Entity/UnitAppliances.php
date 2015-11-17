<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UnitAppliances
 * @ORM\Table(name="unit_appliances")
 * @ORM\Entity()
 */
class UnitAppliances {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Unit", mappedBy="appliances")
     */
    private $unit;
    /**
     * @ORM\Column(type="boolean")
     */
    private $airCondition;
    /**
     * @ORM\Column(type="boolean")
     */
    private $fan;
    /**
     * @ORM\Column(type="boolean")
     */
    private $iron;
    /**
     * @ORM\Column(type="boolean")
     */
    private $tv;
    /**
     * @ORM\Column(type="boolean")
     */
    private $flatscreenTv;
    /**
     * @ORM\Column(type="boolean")
     */
    private $satTv;
    /**
     * @ORM\Column(type="boolean")
     */
    private $dvd;
    /**
     * @ORM\Column(type="boolean")
     */
    private $phone;
    /**
     * @ORM\Column(type="boolean")
     */
    private $radio;
    /**
     * @ORM\Column(type="boolean")
     */
    private $pc;
    /**
     * @ORM\Column(type="boolean")
     */
    private $gameConsole;
    /**
     * @ORM\Column(type="boolean")
     */
    private $heating;
    /**
     * @ORM\Column(type="boolean")
     */
    private $safe;
    /**
     * @ORM\Column(type="boolean")
     */
    private $miniBar;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set airCondition
     * @param boolean $airCondition
     * @return UnitAppliances
     */
    public function setAirCondition($airCondition) {
        $this->airCondition = $airCondition;

        return $this;
    }

    /**
     * Get airCondition
     * @return boolean
     */
    public function getAirCondition() {
        return $this->airCondition;
    }

    /**
     * Set fan
     * @param boolean $fan
     * @return UnitAppliances
     */
    public function setFan($fan) {
        $this->fan = $fan;

        return $this;
    }

    /**
     * Get fan
     * @return boolean
     */
    public function getFan() {
        return $this->fan;
    }

    /**
     * Set iron
     * @param boolean $iron
     * @return UnitAppliances
     */
    public function setIron($iron) {
        $this->iron = $iron;

        return $this;
    }

    /**
     * Get iron
     * @return boolean
     */
    public function getIron() {
        return $this->iron;
    }

    /**
     * Set tv
     * @param boolean $tv
     * @return UnitAppliances
     */
    public function setTv($tv) {
        $this->tv = $tv;

        return $this;
    }

    /**
     * Get tv
     * @return boolean
     */
    public function getTv() {
        return $this->tv;
    }

    /**
     * Set flatscreenTv
     * @param boolean $flatscreenTv
     * @return UnitAppliances
     */
    public function setFlatscreenTv($flatscreenTv) {
        $this->flatscreenTv = $flatscreenTv;

        return $this;
    }

    /**
     * Get flatscreenTv
     * @return boolean
     */
    public function getFlatscreenTv() {
        return $this->flatscreenTv;
    }

    /**
     * Set satTv
     * @param boolean $satTv
     * @return UnitAppliances
     */
    public function setSatTv($satTv) {
        $this->satTv = $satTv;

        return $this;
    }

    /**
     * Get satTv
     * @return boolean
     */
    public function getSatTv() {
        return $this->satTv;
    }

    /**
     * Set dvd
     * @param boolean $dvd
     * @return UnitAppliances
     */
    public function setDvd($dvd) {
        $this->dvd = $dvd;

        return $this;
    }

    /**
     * Get dvd
     * @return boolean
     */
    public function getDvd() {
        return $this->dvd;
    }

    /**
     * Set phone
     * @param boolean $phone
     * @return UnitAppliances
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     * @return boolean
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set radio
     * @param boolean $radio
     * @return UnitAppliances
     */
    public function setRadio($radio) {
        $this->radio = $radio;

        return $this;
    }

    /**
     * Get radio
     * @return boolean
     */
    public function getRadio() {
        return $this->radio;
    }

    /**
     * Set pc
     * @param boolean $pc
     * @return UnitAppliances
     */
    public function setPc($pc) {
        $this->pc = $pc;

        return $this;
    }

    /**
     * Get pc
     * @return boolean
     */
    public function getPc() {
        return $this->pc;
    }

    /**
     * Set gameConsole
     * @param boolean $gameConsole
     * @return UnitAppliances
     */
    public function setGameConsole($gameConsole) {
        $this->gameConsole = $gameConsole;

        return $this;
    }

    /**
     * Get gameConsole
     * @return boolean
     */
    public function getGameConsole() {
        return $this->gameConsole;
    }

    /**
     * Set heating
     * @param boolean $heating
     * @return UnitAppliances
     */
    public function setHeating($heating) {
        $this->heating = $heating;

        return $this;
    }

    /**
     * Get heating
     * @return boolean
     */
    public function getHeating() {
        return $this->heating;
    }

    /**
     * Set safe
     * @param boolean $safe
     * @return UnitAppliances
     */
    public function setSafe($safe) {
        $this->safe = $safe;

        return $this;
    }

    /**
     * Get safe
     * @return boolean
     */
    public function getSafe() {
        return $this->safe;
    }

    /**
     * Set miniBar
     * @param boolean $miniBar
     * @return UnitAppliances
     */
    public function setMiniBar($miniBar) {
        $this->miniBar = $miniBar;

        return $this;
    }

    /**
     * Get miniBar
     * @return boolean
     */
    public function getMiniBar() {
        return $this->miniBar;
    }

    /**
     * Set unit
     * @param \AppBundle\Entity\Unit $unit
     * @return UnitAppliances
     */
    public function setUnit(\AppBundle\Entity\Unit $unit = null) {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     * @return \AppBundle\Entity\Unit
     */
    public function getUnit() {
        return $this->unit;
    }
}
