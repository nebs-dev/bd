<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UnitPeriod
 * @ORM\Table(name="unit_periods")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UnitPeriodRepository")
 */
class UnitPeriod {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="fromDate", type="date")
     */
    private $fromDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="toDate", type="date")
     */
    private $toDate;

    /**
     * @var integer
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="periods")
     */
    private $unit;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set fromDate
     * @param \DateTime $fromDate
     * @return UnitPeriod
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
     * @return UnitPeriod
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
     * Set amount
     * @param integer $amount
     * @return UnitPeriod
     */
    public function setAmount($amount) {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get amount
     * @return integer
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set unit
     * @param \AppBundle\Entity\Unit $unit
     * @return UnitPeriod
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
