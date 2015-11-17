<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="advertising_package_types")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AdvertisingPackageTypeRepository")
 */
class AdvertisingPackageType {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToMany(targetEntity="AdvertisingPackage", mappedBy="type", cascade={"all"})
     */
    private $advertisingPackage;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     */
    private $price;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * Constructor
     */
    public function __construct() {
        $this->advertisingPackage = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return AdvertisingPackageType
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
     * Set price
     * @param integer $price
     * @return AdvertisingPackageType
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
     * Add advertisingPackage
     * @param \AppBundle\Entity\AdvertisingPackage $advertisingPackage
     * @return AdvertisingPackageType
     */
    public function addAdvertisingPackage(\AppBundle\Entity\AdvertisingPackage $advertisingPackage) {
        $this->advertisingPackage[] = $advertisingPackage;

        return $this;
    }

    /**
     * Remove advertisingPackage
     * @param \AppBundle\Entity\AdvertisingPackage $advertisingPackage
     */
    public function removeAdvertisingPackage(\AppBundle\Entity\AdvertisingPackage $advertisingPackage) {
        $this->advertisingPackage->removeElement($advertisingPackage);
    }

    /**
     * Get advertisingPackage
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvertisingPackage() {
        return $this->advertisingPackage;
    }

    /**
     * Set type
     * @param string $type
     * @return AdvertisingPackageType
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     * @return string
     */
    public function getType() {
        return $this->type;
    }

}
