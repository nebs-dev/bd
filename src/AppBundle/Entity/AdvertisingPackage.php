<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="advertising_packages")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AdvertisingPackageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AdvertisingPackage {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AdvertisingPackageType", inversedBy="advertisingPackage")
     */
    private $type;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $validUntil;
    /**
     * @ORM\Column(type="boolean")
     */
    private $status = false;
    /**
     * @ORM\ManyToOne(targetEntity="Accommodation", inversedBy="advertisingPackage", cascade={"persist"})
     */
    private $accommodation;
    /**
     * created Time/Date
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;


//    /**
//     * Set validUntil - now
//     * @ORM\PrePersist
//     */
//    public function setValidDate() {
//        $this->setValidUntil(new \DateTime());
//    }

    /**
     * Set createdAt
     * @ORM\PrePersist
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime();
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set validUntil
     * @param string $validUntil
     * @return AdvertisingPackage
     */
    public function setValidUntil($validUntil) {
        $this->validUntil = $validUntil;

        return $this;
    }

    /**
     * Get validUntil
     * @return string
     */
    public function getValidUntil() {
        return $this->validUntil;
    }

    /**
     * Set status
     * @param boolean $status
     * @return AdvertisingPackage
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     * @return boolean
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set type
     * @param \AppBundle\Entity\AdvertisingPackageType $type
     * @return AdvertisingPackage
     */
    public function setType(\AppBundle\Entity\AdvertisingPackageType $type = null) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     * @return \AppBundle\Entity\AdvertisingPackageType
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set accommodation
     * @param \AppBundle\Entity\Accommodation $accommodation
     * @return AdvertisingPackage
     */
    public function setAccommodation(\AppBundle\Entity\Accommodation $accommodation = null) {
        $this->accommodation = $accommodation;

        return $this;
    }

    /**
     * Get accommodation
     * @return \AppBundle\Entity\Accommodation
     */
    public function getAccommodation() {
        return $this->accommodation;
    }
}
