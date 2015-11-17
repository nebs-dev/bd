<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UnitBedroom
 * @ORM\Table(name="unit_bedroom")
 * @ORM\Entity()
 */
class UnitBedroom {
	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\OneToOne(targetEntity="Unit", mappedBy="bedroom")
	 */
	private $unit;
	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $number;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $sheets;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $wardrobe;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $mosquitoNet;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $soundproofing;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $worktable;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $terraceAccess;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $balconyAccess;

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
	 * @return UnitBedroom
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
	 * Set sheets
	 * @param boolean $sheets
	 * @return UnitBedroom
	 */
	public function setSheets($sheets) {
		$this->sheets = $sheets;
		return $this;
	}

	/**
	 * Get sheets
	 * @return boolean
	 */
	public function getSheets() {
		return $this->sheets;
	}

	/**
	 * Set wardrobe
	 * @param boolean $wardrobe
	 * @return UnitBedroom
	 */
	public function setWardrobe($wardrobe) {
		$this->wardrobe = $wardrobe;
		return $this;
	}

	/**
	 * Get wardrobe
	 * @return boolean
	 */
	public function getWardrobe() {
		return $this->wardrobe;
	}

	/**
	 * Set mosquitoNet
	 * @param boolean $mosquitoNet
	 * @return UnitBedroom
	 */
	public function setMosquitoNet($mosquitoNet) {
		$this->mosquitoNet = $mosquitoNet;
		return $this;
	}

	/**
	 * Get mosquitoNet
	 * @return boolean
	 */
	public function getMosquitoNet() {
		return $this->mosquitoNet;
	}

	/**
	 * Set soundproofing
	 * @param boolean $soundproofing
	 * @return UnitBedroom
	 */
	public function setSoundproofing($soundproofing) {
		$this->soundproofing = $soundproofing;
		return $this;
	}

	/**
	 * Get soundproofing
	 * @return boolean
	 */
	public function getSoundproofing() {
		return $this->soundproofing;
	}

	/**
	 * Set worktable
	 * @param boolean $worktable
	 * @return UnitBedroom
	 */
	public function setWorktable($worktable) {
		$this->worktable = $worktable;
		return $this;
	}

	/**
	 * Get worktable
	 * @return boolean
	 */
	public function getWorktable() {
		return $this->worktable;
	}

	/**
	 * Set terraceAccess
	 * @param boolean $terraceAccess
	 * @return UnitBedroom
	 */
	public function setTerraceAccess($terraceAccess) {
		$this->terraceAccess = $terraceAccess;
		return $this;
	}

	/**
	 * Get terraceAccess
	 * @return boolean
	 */
	public function getTerraceAccess() {
		return $this->terraceAccess;
	}

	/**
	 * Set balconyAccess
	 * @param boolean $balconyAccess
	 * @return UnitBedroom
	 */
	public function setBalconyAccess($balconyAccess) {
		$this->balconyAccess = $balconyAccess;
		return $this;
	}

	/**
	 * Get balconyAccess
	 * @return boolean
	 */
	public function getBalconyAccess() {
		return $this->balconyAccess;
	}

	/**
	 * Set unit
	 * @param \AppBundle\Entity\Unit $unit
	 * @return UnitBedroom
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
