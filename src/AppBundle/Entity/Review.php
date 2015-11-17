<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="reviews")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ReviewRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Review {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="reviews")
     */
    private $tourist;
    /**
     * @ORM\ManyToOne(targetEntity="Accommodation", inversedBy="reviews")
     */
    private $accommodation;
    /**
     * @ORM\Column(type="smallint")
     */
    private $cleanliness;
    /**
     * @ORM\Column(type="smallint")
     */
    private $comfort;
    /**
     * @ORM\Column(type="smallint")
     */
    private $location;
    /**
     * @ORM\Column(type="smallint")
     */
    private $facilities;
    /**
     * @ORM\Column(type="smallint")
     */
    private $staff;
    /**
     * @ORM\Column(type="smallint")
     */
    private $valueForMoney;
    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $total;
    /**
     * @ORM\Column(type="text")
     */
    private $text;
    /**
     * @ORM\Column(type="boolean")
     */
    private $status;
    /**
     * created Time/Date
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

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
     * Set tourist
     * @param \UserBundle\Entity\User $tourist
     * @return Review
     */
    public function setTourist(\UserBundle\Entity\User $tourist = null) {
        $this->tourist = $tourist;

        return $this;
    }

    /**
     * Get tourist
     * @return \UserBundle\Entity\User
     */
    public function getTourist() {
        return $this->tourist;
    }

    /**
     * Set accommodation
     * @param \AppBundle\Entity\Accommodation $accommodation
     * @return Review
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

    /**
     * Set cleanliness
     * @param integer $cleanliness
     * @return Review
     */
    public function setCleanliness($cleanliness) {
        $this->cleanliness = $cleanliness;

        return $this;
    }

    /**
     * Get cleanliness
     * @return integer
     */
    public function getCleanliness() {
        return $this->cleanliness;
    }

    /**
     * Set comfort
     * @param integer $comfort
     * @return Review
     */
    public function setComfort($comfort) {
        $this->comfort = $comfort;

        return $this;
    }

    /**
     * Get comfort
     * @return integer
     */
    public function getComfort() {
        return $this->comfort;
    }

    /**
     * Set location
     * @param integer $location
     * @return Review
     */
    public function setLocation($location) {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     * @return integer
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set facilities
     * @param integer $facilities
     * @return Review
     */
    public function setFacilities($facilities) {
        $this->facilities = $facilities;

        return $this;
    }

    /**
     * Get facilities
     * @return integer
     */
    public function getFacilities() {
        return $this->facilities;
    }

    /**
     * Set staff
     * @param integer $staff
     * @return Review
     */
    public function setStaff($staff) {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     * @return integer
     */
    public function getStaff() {
        return $this->staff;
    }

    /**
     * Set valueForMoney
     * @param integer $valueForMoney
     * @return Review
     */
    public function setValueForMoney($valueForMoney) {
        $this->valueForMoney = $valueForMoney;

        return $this;
    }

    /**
     * Get valueForMoney
     * @return integer
     */
    public function getValueForMoney() {
        return $this->valueForMoney;
    }

    /**
     * Set total
     * @param integer $total
     * @return Review
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     * @return integer
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set text
     * @param string $text
     * @return Review
     */
    public function setText($text) {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Set status
     * @param boolean $status
     * @return Review
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
}
