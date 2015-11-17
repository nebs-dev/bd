<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="accommodation_payments")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AccommodationPaymentRepository")
 */
class AccommodationPayment {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $name;
	/**
	 * @ORM\ManyToMany(targetEntity="Accommodation", mappedBy="payments")
	 */
	private $accommodations;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->accommodations = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return AccommodationPayment
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
	 * Add accommodations
	 * @param \AppBundle\Entity\Accommodation $accommodations
	 * @return AccommodationPayment
	 */
	public function addAccommodation(\AppBundle\Entity\Accommodation $accommodations) {
		$this->accommodations[] = $accommodations;
		return $this;
	}

	/**
	 * Remove accommodations
	 * @param \AppBundle\Entity\Accommodation $accommodations
	 */
	public function removeAccommodation(\AppBundle\Entity\Accommodation $accommodations) {
		$this->accommodations->removeElement($accommodations);
	}

	/**
	 * Get accommodations
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getAccommodations() {
		return $this->accommodations;
	}
}
