<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UnitLivingroom
 * @ORM\Table(name="unit_livingroom")
 * @ORM\Entity()
 */
class UnitLivingroom {
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Unit", mappedBy="livingroom")
     */
    private $unit;
    /**
     * @ORM\Column(type="boolean")
     */
    private $modernFurniture;
    /**
     * @ORM\Column(type="boolean")
     */
    private $rusticFurniture;
    /**
     * @ORM\Column(type="boolean")
     */
    private $sofas;
    /**
     * @ORM\Column(type="boolean")
     */
    private $balconyAccess;
    /**
     * @ORM\Column(type="boolean")
     */
    private $terraceAccess;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set modernFurniture
     * @param boolean $modernFurniture
     * @return UnitLivingroom
     */
    public function setModernFurniture($modernFurniture) {
        $this->modernFurniture = $modernFurniture;
        return $this;
    }

    /**
     * Get modernFurniture
     * @return boolean
     */
    public function getModernFurniture() {
        return $this->modernFurniture;
    }

    /**
     * Set rusticFurniture
     * @param boolean $rusticFurniture
     * @return UnitLivingroom
     */
    public function setRusticFurniture($rusticFurniture) {
        $this->rusticFurniture = $rusticFurniture;
        return $this;
    }

    /**
     * Get rusticFurniture
     * @return boolean
     */
    public function getRusticFurniture() {
        return $this->rusticFurniture;
    }

    /**
     * Set sofas
     * @param boolean $sofas
     * @return UnitLivingroom
     */
    public function setSofas($sofas) {
        $this->sofas = $sofas;
        return $this;
    }

    /**
     * Get sofas
     * @return boolean
     */
    public function getSofas() {
        return $this->sofas;
    }

    /**
     * Set balconyAccess
     * @param boolean $balconyAccess
     * @return UnitLivingroom
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
     * Set terraceAccess
     * @param boolean $terraceAccess
     * @return UnitLivingroom
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
     * Set unit
     * @param \AppBundle\Entity\Unit $unit
     * @return UnitLivingroom
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
