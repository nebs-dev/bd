<?php

namespace AppBundle\Entity\AdditionalOffer;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="additional_offer_categories")
 * @ORM\Entity()
 */
class Category {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AdditionalOffer", mappedBy="category")
     */
    private $additionalOffer;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * Constructor
     */
    public function __construct() {
        $this->additionalOffer = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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
     * Add additionalOffer
     * @param \AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer
     * @return Category
     */
    public function addAdditionalOffer(\AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer) {
        $this->additionalOffer[] = $additionalOffer;

        return $this;
    }

    /**
     * Remove additionalOffer
     * @param \AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer
     */
    public function removeAdditionalOffer(\AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer) {
        $this->additionalOffer->removeElement($additionalOffer);
    }

    /**
     * Get additionalOffer
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdditionalOffer() {
        return $this->additionalOffer;
    }
}
