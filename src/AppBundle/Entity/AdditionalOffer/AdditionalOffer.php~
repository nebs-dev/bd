<?php

namespace AppBundle\Entity\AdditionalOffer;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="additional_offers")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AdditionalOffer\Repository\AdditionalOfferRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AdditionalOffer {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Detail", inversedBy="additionalOffer", cascade={"all"})
     */
    private $details;
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="additionalOffer")
     */
    private $category;
    /**
     * @ORM\OneToOne(targetEntity="Description", inversedBy="additionalOffer", cascade={"all"})
     */
    private $descriptions;
    /**
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Country", inversedBy="additionalOffers")
     */
    private $country;
    /**
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Region", inversedBy="additionalOffers")
     */
    private $region;
    /**
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Subregion", inversedBy="additionalOffers")
     */
    private $subregion;
    /**
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\City", inversedBy="additionalOffers")
     */
    private $city;
    /**
     * @ORM\OneToMany(targetEntity="Gallery", mappedBy="additionalOffer", cascade={"all"})
     */
    private $gallery;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="additionalOffer")
     */
    private $host;
    /**
     * @ORM\Column(type="boolean")
     */
    private $status;
    /**
     * @ORM\Column(type="date")
     */
    private $validUntil;
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
        $this->gallery = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }


    public function getFeaturedImage() {
        if (count($this->getGallery()) > 0) {
            foreach ($this->getGallery() as $photo) {
                return $photo->getWebPath();
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
     * Set details
     * @param \AppBundle\Entity\AdditionalOffer\Detail $details
     * @return AdditionalOffer
     */
    public function setDetails(\AppBundle\Entity\AdditionalOffer\Detail $details = null) {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     * @return \AppBundle\Entity\AdditionalOffer\Detail
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * Set category
     * @param \AppBundle\Entity\AdditionalOffer\Category $category
     * @return AdditionalOffer
     */
    public function setCategory(\AppBundle\Entity\AdditionalOffer\Category $category = null) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     * @return \AppBundle\Entity\AdditionalOffer\Category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set descriptions
     * @param \AppBundle\Entity\AdditionalOffer\Description $descriptions
     * @return AdditionalOffer
     */
    public function setDescriptions(\AppBundle\Entity\AdditionalOffer\Description $descriptions = null) {
        $this->descriptions = $descriptions;

        return $this;
    }

    /**
     * Get descriptions
     * @return \AppBundle\Entity\AdditionalOffer\Description
     */
    public function getDescriptions() {
        return $this->descriptions;
    }

    /**
     * Set country
     * @param \AppBundle\Entity\Country $country
     * @return AdditionalOffer
     */
    public function setCountry(\AppBundle\Entity\Country $country = null) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     * @return \AppBundle\Entity\Country
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set region
     * @param \AppBundle\Entity\Region $region
     * @return AdditionalOffer
     */
    public function setRegion(\AppBundle\Entity\Region $region = null) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     * @return \AppBundle\Entity\Region
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Set subregion
     * @param \AppBundle\Entity\Subregion $subregion
     * @return AdditionalOffer
     */
    public function setSubregion(\AppBundle\Entity\Subregion $subregion = null) {
        $this->subregion = $subregion;

        return $this;
    }

    /**
     * Get subregion
     * @return \AppBundle\Entity\Subregion
     */
    public function getSubregion() {
        return $this->subregion;
    }

    /**
     * Set city
     * @param \AppBundle\Entity\City $city
     * @return AdditionalOffer
     */
    public function setCity(\AppBundle\Entity\City $city = null) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     * @return \AppBundle\Entity\City
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Add gallery
     * @param \AppBundle\Entity\AdditionalOffer\Gallery $gallery
     * @return AdditionalOffer
     */
    public function addGallery(\AppBundle\Entity\AdditionalOffer\Gallery $gallery) {
        $this->gallery[] = $gallery;
        $gallery->setAdditionalOffer($this);

        return $this;
    }

    /**
     * Remove gallery
     * @param \AppBundle\Entity\AdditionalOffer\Gallery $gallery
     */
    public function removeGallery(\AppBundle\Entity\AdditionalOffer\Gallery $gallery) {
        $this->gallery->removeElement($gallery);
    }

    /**
     * Get gallery
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGallery() {
        return $this->gallery;
    }

    /**
     * Set status
     * @param boolean $status
     * @return AdditionalOffer
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
     * @return AdditionalOffer
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
     * Set host
     *
     * @param \UserBundle\Entity\User $host
     * @return AdditionalOffer
     */
    public function setHost(\UserBundle\Entity\User $host = null)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return \UserBundle\Entity\User 
     */
    public function getHost()
    {
        return $this->host;
    }
}
