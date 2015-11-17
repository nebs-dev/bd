<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="unit_category")
 * @ORM\Entity()
 */
class UnitCategory {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(name="name", type="string", length=30)
	 */
	private $name;
	/**
	 * @ORM\OneToMany(targetEntity="Unit", mappedBy="unitCategory")
	 */
	private $units;

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
	 * Set name
	 * @param string $name
	 * @return UnitCategory
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
	 * Add units
	 * @param \AppBundle\Entity\Unit $units
	 * @return UnitCategory
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
}
