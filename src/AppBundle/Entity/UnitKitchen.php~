<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UnitKitchen
 * @ORM\Table(name="unit_kitchen")
 * @ORM\Entity()
 */
class UnitKitchen {
	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\OneToOne(targetEntity="Unit", mappedBy="kitchen")
	 */
	private $unit;
	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $type;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $dishes;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $cutlery;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $fridge;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $rings;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $electricKettle;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $coffeeMachine;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $diningroom;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $microwave;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $toaster;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $childrenChair;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $tableChairs;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $oven;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $dishwasher;

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set dishes
	 * @param boolean $dishes
	 * @return UnitKitchen
	 */
	public function setDishes($dishes) {
		$this->dishes = $dishes;
		return $this;
	}

	/**
	 * Get dishes
	 * @return boolean
	 */
	public function getDishes() {
		return $this->dishes;
	}

	/**
	 * Set cutlery
	 * @param boolean $cutlery
	 * @return UnitKitchen
	 */
	public function setCutlery($cutlery) {
		$this->cutlery = $cutlery;
		return $this;
	}

	/**
	 * Get cutlery
	 * @return boolean
	 */
	public function getCutlery() {
		return $this->cutlery;
	}

	/**
	 * Set fridge
	 * @param boolean $fridge
	 * @return UnitKitchen
	 */
	public function setFridge($fridge) {
		$this->fridge = $fridge;
		return $this;
	}

	/**
	 * Get fridge
	 * @return boolean
	 */
	public function getFridge() {
		return $this->fridge;
	}

	/**
	 * Set rings
	 * @param boolean $rings
	 * @return UnitKitchen
	 */
	public function setRings($rings) {
		$this->rings = $rings;
		return $this;
	}

	/**
	 * Get rings
	 * @return boolean
	 */
	public function getRings() {
		return $this->rings;
	}

	/**
	 * Set electricKettle
	 * @param boolean $electricKettle
	 * @return UnitKitchen
	 */
	public function setElectricKettle($electricKettle) {
		$this->electricKettle = $electricKettle;
		return $this;
	}

	/**
	 * Get electricKettle
	 * @return boolean
	 */
	public function getElectricKettle() {
		return $this->electricKettle;
	}

	/**
	 * Set coffeeMachine
	 * @param boolean $coffeeMachine
	 * @return UnitKitchen
	 */
	public function setCoffeeMachine($coffeeMachine) {
		$this->coffeeMachine = $coffeeMachine;
		return $this;
	}

	/**
	 * Get coffeeMachine
	 * @return boolean
	 */
	public function getCoffeeMachine() {
		return $this->coffeeMachine;
	}

	/**
	 * Set diningroom
	 * @param boolean $diningroom
	 * @return UnitKitchen
	 */
	public function setDiningroom($diningroom) {
		$this->diningroom = $diningroom;
		return $this;
	}

	/**
	 * Get diningroom
	 * @return boolean
	 */
	public function getDiningroom() {
		return $this->diningroom;
	}

	/**
	 * Set microwave
	 * @param boolean $microwave
	 * @return UnitKitchen
	 */
	public function setMicrowave($microwave) {
		$this->microwave = $microwave;
		return $this;
	}

	/**
	 * Get microwave
	 * @return boolean
	 */
	public function getMicrowave() {
		return $this->microwave;
	}

	/**
	 * Set toaster
	 * @param boolean $toaster
	 * @return UnitKitchen
	 */
	public function setToaster($toaster) {
		$this->toaster = $toaster;
		return $this;
	}

	/**
	 * Get toaster
	 * @return boolean
	 */
	public function getToaster() {
		return $this->toaster;
	}

	/**
	 * Set childrenChair
	 * @param boolean $childrenChair
	 * @return UnitKitchen
	 */
	public function setChildrenChair($childrenChair) {
		$this->childrenChair = $childrenChair;
		return $this;
	}

	/**
	 * Get childrenChair
	 * @return boolean
	 */
	public function getChildrenChair() {
		return $this->childrenChair;
	}

	/**
	 * Set tableChairs
	 * @param boolean $tableChairs
	 * @return UnitKitchen
	 */
	public function setTableChairs($tableChairs) {
		$this->tableChairs = $tableChairs;
		return $this;
	}

	/**
	 * Get tableChairs
	 * @return boolean
	 */
	public function getTableChairs() {
		return $this->tableChairs;
	}

	/**
	 * Set oven
	 * @param boolean $oven
	 * @return UnitKitchen
	 */
	public function setOven($oven) {
		$this->oven = $oven;
		return $this;
	}

	/**
	 * Get oven
	 * @return boolean
	 */
	public function getOven() {
		return $this->oven;
	}

	/**
	 * Set dishwasher
	 * @param boolean $dishwasher
	 * @return UnitKitchen
	 */
	public function setDishwasher($dishwasher) {
		$this->dishwasher = $dishwasher;
		return $this;
	}

	/**
	 * Get dishwasher
	 * @return boolean
	 */
	public function getDishwasher() {
		return $this->dishwasher;
	}

	/**
	 * Set unit
	 * @param \AppBundle\Entity\Unit $unit
	 * @return UnitKitchen
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
     * Set type
     *
     * @param string $type
     * @return UnitKitchen
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
