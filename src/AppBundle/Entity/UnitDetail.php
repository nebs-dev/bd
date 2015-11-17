<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="unit_details")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UnitDetailRepository")
 */
class UnitDetail {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToMany(targetEntity="Unit", mappedBy="details")
     */
    private $unit;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $doubleBed;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $singleBed;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $sofaBed;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $extraBed;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $cot;
    /**
     * @ORM\Column(type="boolean")
     */
    private $privateEntrance;
    /**
     * @ORM\Column(type="boolean")
     */
    private $accessDisabled;
    /**
     * @ORM\Column(type="boolean")
     */
    private $smokingAllowed;
    /**
     * @ORM\Column(type="boolean")
     */
    private $corridor;

    /**
     * Constructor
     */
    public function __construct() {
        $this->units = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set doubleBed
     * @param integer $doubleBed
     * @return UnitDetail
     */
    public function setDoubleBed($doubleBed) {
        $this->doubleBed = $doubleBed;
        return $this;
    }

    /**
     * Get doubleBed
     * @return integer
     */
    public function getDoubleBed() {
        return $this->doubleBed;
    }

    /**
     * Set singleBed
     * @param integer $singleBed
     * @return UnitDetail
     */
    public function setSingleBed($singleBed) {
        $this->singleBed = $singleBed;
        return $this;
    }

    /**
     * Get singleBed
     * @return integer
     */
    public function getSingleBed() {
        return $this->singleBed;
    }

    /**
     * Set sofaBed
     * @param integer $sofaBed
     * @return UnitDetail
     */
    public function setSofaBed($sofaBed) {
        $this->sofaBed = $sofaBed;
        return $this;
    }

    /**
     * Get sofaBed
     * @return integer
     */
    public function getSofaBed() {
        return $this->sofaBed;
    }

    /**
     * Set extraBed
     * @param integer $extraBed
     * @return UnitDetail
     */
    public function setExtraBed($extraBed) {
        $this->extraBed = $extraBed;
        return $this;
    }

    /**
     * Get extraBed
     * @return integer
     */
    public function getExtraBed() {
        return $this->extraBed;
    }

    /**
     * Set cot
     * @param integer $cot
     * @return UnitDetail
     */
    public function setCot($cot) {
        $this->cot = $cot;
        return $this;
    }

    /**
     * Get cot
     * @return integer
     */
    public function getCot() {
        return $this->cot;
    }

    /**
     * Set privateEntrance
     * @param boolean $privateEntrance
     * @return UnitDetail
     */
    public function setPrivateEntrance($privateEntrance) {
        $this->privateEntrance = $privateEntrance;
        return $this;
    }

    /**
     * Get privateEntrance
     * @return boolean
     */
    public function getPrivateEntrance() {
        return $this->privateEntrance;
    }

    /**
     * Set accessDisabled
     * @param boolean $accessDisabled
     * @return UnitDetail
     */
    public function setAccessDisabled($accessDisabled) {
        $this->accessDisabled = $accessDisabled;
        return $this;
    }

    /**
     * Get accessDisabled
     * @return boolean
     */
    public function getAccessDisabled() {
        return $this->accessDisabled;
    }

    /**
     * Set smokingAllowed
     * @param boolean $smokingAllowed
     * @return UnitDetail
     */
    public function setSmokingAllowed($smokingAllowed) {
        $this->smokingAllowed = $smokingAllowed;
        return $this;
    }

    /**
     * Get smokingAllowed
     * @return boolean
     */
    public function getSmokingAllowed() {
        return $this->smokingAllowed;
    }

    /**
     * Add units
     * @param \AppBundle\Entity\Unit $units
     * @return UnitDetail
     */
    public function addUnit(\AppBundle\Entity\Unit $units) {
        $this->units[] = $units;
        return $this;
    }

    /**
     * Remove units
     * @param \AppBundle\Entity\Unit $units
     */
    public function removeUnit(\AppBundle\Entity\Unit $units) {
        $this->units->removeElement($units);
    }

    /**
     * Get units
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnits() {
        return $this->units;
    }

    /**
     * Get unit
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnit() {
        return $this->unit;
    }

    /**
     * Set corridor
     * @param boolean $corridor
     * @return UnitDetail
     */
    public function setCorridor($corridor) {
        $this->corridor = $corridor;

        return $this;
    }

    /**
     * Get corridor
     * @return boolean
     */
    public function getCorridor() {
        return $this->corridor;
    }
}
