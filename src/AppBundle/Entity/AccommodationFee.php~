<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="accommodation_fees")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AccommodationFeeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationFee {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Accommodation", inversedBy="fees")
     */
    private $accommodation;
    /**
     * @ORM\Column(type="boolean")
     */
    private $status = true;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $validUntil;
    /**
     * @ORM\ManyToOne(targetEntity="AccommodationFeeType", inversedBy="fees")
     */
    private $type;
    /**
     * created Time/Date
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * Constructor
     */
    public function __construct() {
        $this->accommodations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Check if accommodation fee is valid (active)
     * @return bool
     */
    public function isValid() {
        if ($this->getStatus()) {
            $validUntil = (!is_null($this->getValidUntil())) ? $this->getValidUntil()->format('Y-m-d') : '1900-01-01';
            if ((($validUntil >= date("Y-m-d")) && ($this->getType()->getPayment())) || ($this->getType()->getPayment() == 0)) {
                return true;
            }
        }

        return false;
    }

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
     * Set status on create (depends on type payment method)
     * @ORM\PrePersist
     */
//    public function setStatusCreate() {
//        if($this->getType()->getPayment()) {
//            $this->setStatus(0);
//        } else {
//            $this->setStatus(1);
//        }
//    }

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set status
     * @param boolean $status
     * @return AccommodationFee
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
     * Set validUntil
     * @param \DateTime $validUntil
     * @return AccommodationFee
     */
    public function setValidUntil($validUntil) {
        $this->validUntil = $validUntil;

        return $this;
    }

    /**
     * Get validUntil
     * @return \DateTime
     */
    public function getValidUntil() {
        return $this->validUntil;
    }

    /**
     * Set type
     * @param \AppBundle\Entity\AccommodationFeeType $type
     * @return AccommodationFee
     */
    public function setType(\AppBundle\Entity\AccommodationFeeType $type = null) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     * @return \AppBundle\Entity\AccommodationFeeType
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set accommodation
     * @param \AppBundle\Entity\Accommodation $accommodation
     * @return AccommodationFee
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
