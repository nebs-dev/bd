<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="unit_price_extra")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UnitPriceExtraRepository")
 */
class UnitPriceExtra {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="unitPriceExtra")
     */
    private $unit;
    /**
     * @ORM\ManyToOne(targetEntity="PriceExtra", inversedBy="unitPriceExtra")
     */
    private $priceExtra;
    /**
     * @ORM\Column(type="boolean")
     */
    private $availability;
    /**
     * @ORM\Column(type="decimal", precision=14, scale=10, nullable=true)
     */
    private $price;


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
     * @return UnitPriceExtra
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
     * Set availability
     * @param boolean $availability
     * @return UnitPriceExtra
     */
    public function setAvailability($availability) {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability
     * @return boolean
     */
    public function getAvailability() {
        return $this->availability;
    }

    /**
     * Set price
     * @param integer $price
     * @return UnitPriceExtra
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     * @return integer
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set unit
     * @param \AppBundle\Entity\Unit $unit
     * @return UnitPriceExtra
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
     * Set priceExtra
     * @param \AppBundle\Entity\PriceExtra $priceExtra
     * @return UnitPriceExtra
     */
    public function setPriceExtra(\AppBundle\Entity\PriceExtra $priceExtra = null) {
        $this->priceExtra = $priceExtra;

        return $this;
    }

    /**
     * Get priceExtra
     * @return \AppBundle\Entity\PriceExtra
     */
    public function getPriceExtra() {
        return $this->priceExtra;
    }
}
