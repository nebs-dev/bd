<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UnitBathroom
 * @ORM\Table(name="unit_bathroom")
 * @ORM\Entity()
 */
class UnitBathroom {
	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\OneToOne(targetEntity="Unit", mappedBy="bathroom")
	 */
	private $unit;
	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $number;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $bathtub;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $bidet;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $shower;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $jacuzzi;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $sauna;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $bathrobe;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $slippers;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $toiletries;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $hairDryer;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $sharedToilet;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $separateToilet;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $washingMachine;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $clothesDryer;
    /**
     * @ORM\Column(type="boolean")
     */
    private $towels;

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set number
	 * @param integer $number
	 * @return UnitBathroom
	 */
	public function setNumber($number) {
		$this->number = $number;
		return $this;
	}

	/**
	 * Get number
	 * @return integer
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * Set bathtub
	 * @param boolean $bathtub
	 * @return UnitBathroom
	 */
	public function setBathtub($bathtub) {
		$this->bathtub = $bathtub;
		return $this;
	}

	/**
	 * Get bathtub
	 * @return boolean
	 */
	public function getBathtub() {
		return $this->bathtub;
	}

	/**
	 * Set bidet
	 * @param boolean $bidet
	 * @return UnitBathroom
	 */
	public function setBidet($bidet) {
		$this->bidet = $bidet;
		return $this;
	}

	/**
	 * Get bidet
	 * @return boolean
	 */
	public function getBidet() {
		return $this->bidet;
	}

	/**
	 * Set shower
	 * @param boolean $shower
	 * @return UnitBathroom
	 */
	public function setShower($shower) {
		$this->shower = $shower;
		return $this;
	}

	/**
	 * Get shower
	 * @return boolean
	 */
	public function getShower() {
		return $this->shower;
	}

	/**
	 * Set jacuzzi
	 * @param boolean $jacuzzi
	 * @return UnitBathroom
	 */
	public function setJacuzzi($jacuzzi) {
		$this->jacuzzi = $jacuzzi;
		return $this;
	}

	/**
	 * Get jacuzzi
	 * @return boolean
	 */
	public function getJacuzzi() {
		return $this->jacuzzi;
	}

	/**
	 * Set sauna
	 * @param boolean $sauna
	 * @return UnitBathroom
	 */
	public function setSauna($sauna) {
		$this->sauna = $sauna;
		return $this;
	}

	/**
	 * Get sauna
	 * @return boolean
	 */
	public function getSauna() {
		return $this->sauna;
	}

	/**
	 * Set bathrobe
	 * @param boolean $bathrobe
	 * @return UnitBathroom
	 */
	public function setBathrobe($bathrobe) {
		$this->bathrobe = $bathrobe;
		return $this;
	}

	/**
	 * Get bathrobe
	 * @return boolean
	 */
	public function getBathrobe() {
		return $this->bathrobe;
	}

	/**
	 * Set slippers
	 * @param boolean $slippers
	 * @return UnitBathroom
	 */
	public function setSlippers($slippers) {
		$this->slippers = $slippers;
		return $this;
	}

	/**
	 * Get slippers
	 * @return boolean
	 */
	public function getSlippers() {
		return $this->slippers;
	}

	/**
	 * Set toiletries
	 * @param boolean $toiletries
	 * @return UnitBathroom
	 */
	public function setToiletries($toiletries) {
		$this->toiletries = $toiletries;
		return $this;
	}

	/**
	 * Get toiletries
	 * @return boolean
	 */
	public function getToiletries() {
		return $this->toiletries;
	}

	/**
	 * Set hairDryer
	 * @param boolean $hairDryer
	 * @return UnitBathroom
	 */
	public function setHairDryer($hairDryer) {
		$this->hairDryer = $hairDryer;
		return $this;
	}

	/**
	 * Get hairDryer
	 * @return boolean
	 */
	public function getHairDryer() {
		return $this->hairDryer;
	}

	/**
	 * Set sharedToilet
	 * @param boolean $sharedToilet
	 * @return UnitBathroom
	 */
	public function setSharedToilet($sharedToilet) {
		$this->sharedToilet = $sharedToilet;
		return $this;
	}

	/**
	 * Get sharedToilet
	 * @return boolean
	 */
	public function getSharedToilet() {
		return $this->sharedToilet;
	}

	/**
	 * Set separateToilet
	 * @param boolean $separateToilet
	 * @return UnitBathroom
	 */
	public function setSeparateToilet($separateToilet) {
		$this->separateToilet = $separateToilet;
		return $this;
	}

	/**
	 * Get separateToilet
	 * @return boolean
	 */
	public function getSeparateToilet() {
		return $this->separateToilet;
	}

	/**
	 * Set washingMachine
	 * @param boolean $washingMachine
	 * @return UnitBathroom
	 */
	public function setWashingMachine($washingMachine) {
		$this->washingMachine = $washingMachine;
		return $this;
	}

	/**
	 * Get washingMachine
	 * @return boolean
	 */
	public function getWashingMachine() {
		return $this->washingMachine;
	}

	/**
	 * Set clothesDryer
	 * @param boolean $clothesDryer
	 * @return UnitBathroom
	 */
	public function setClothesDryer($clothesDryer) {
		$this->clothesDryer = $clothesDryer;
		return $this;
	}

	/**
	 * Get clothesDryer
	 * @return boolean
	 */
	public function getClothesDryer() {
		return $this->clothesDryer;
	}

	/**
	 * Set unit
	 * @param \AppBundle\Entity\Unit $unit
	 * @return UnitBathroom
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

    /**
     * Set towels
     *
     * @param boolean $towels
     * @return UnitBathroom
     */
    public function setTowels($towels)
    {
        $this->towels = $towels;

        return $this;
    }

    /**
     * Get towels
     *
     * @return boolean 
     */
    public function getTowels()
    {
        return $this->towels;
    }
}
