<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="bookings")
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="bookings")
     */
    private $user;

    /**
     * @ORM\Column(type="date")
     */
    private $fromDate;

    /**
     * @ORM\Column(type="date")
     */
    private $toDate;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    private $status;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $new = 1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $commision;

    /**
     * created Time/Date
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
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
     * Set status
     * @param boolean $status
     * @return Booking
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
     * Set price
     * @param string $price
     * @return Booking
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     * @return string
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set unit
     * @param \AppBundle\Entity\Unit $unit
     * @return Booking
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
     * Set user
     * @param \UserBundle\Entity\User $user
     * @return Booking
     */
    public function setUser(\UserBundle\Entity\User $user = null) {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     * @return \UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set fromDate
     * @param \DateTime $fromDate
     * @return Booking
     */
    public function setFromDate($fromDate) {
        $this->fromDate = $fromDate;
        return $this;
    }

    /**
     * Get fromDate
     * @return \DateTime
     */
    public function getFromDate() {
        return $this->fromDate;
    }

    /**
     * Set toDate
     * @param \DateTime $toDate
     * @return Booking
     */
    public function setToDate($toDate) {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     * @return \DateTime
     */
    public function getToDate() {
        return $this->toDate;
    }

    /**
     * Set new
     * @param boolean $new
     * @return Booking
     */
    public function setNew($new) {
        $this->new = $new;

        return $this;
    }

    /**
     * Get new
     * @return boolean
     */
    public function getNew() {
        return $this->new;
    }

    /**
     * Set commision
     *
     * @param boolean $commision
     * @return Booking
     */
    public function setCommision($commision)
    {
        $this->commision = $commision;

        return $this;
    }

    /**
     * Get commision
     *
     * @return boolean 
     */
    public function getCommision()
    {
        return $this->commision;
    }
}
