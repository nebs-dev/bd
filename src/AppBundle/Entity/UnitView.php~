<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UnitPeriod
 * @ORM\Table(name="unit_views")
 * @ORM\Entity()
 */
class UnitView {
	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $name;
	/**
	 * @ORM\ManyToMany(targetEntity="Unit", mappedBy="views")
	 */
	private $unit;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->unit = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set name
	 * @param string $name
	 * @return UnitView
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Get name
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Add unit
	 * @param \AppBundle\Entity\Unit $unit
	 * @return UnitView
	 */
	public function addUnit(\AppBundle\Entity\Unit $unit) {
		$this->unit[] = $unit;
		return $this;
	}

	/**
	 * Remove unit
	 * @param \AppBundle\Entity\Unit $unit
	 */
	public function removeUnit(\AppBundle\Entity\Unit $unit) {
		$this->unit->removeElement($unit);
	}

	/**
	 * Get unit
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getUnit() {
		return $this->unit;
	}
}
