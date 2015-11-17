<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="units")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UnitRepository")
 */
class Unit {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="UnitCategory", inversedBy="units")
     */
    private $unitCategory;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $position;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;
    /**
     * @ORM\Column(type="smallint")
     */
    private $categorize;
    /**
     * @ORM\Column(type="decimal", scale=1)
     */
    private $surface;
    /**
     * @ORM\Column(type="integer")
     */
    private $basicPrice;
    /**
     * @ORM\Column(type="smallint")
     */
    private $minimumStay;
    /**
     * @ORM\Column(type="smallint")
     */
    private $capacity;
    /**
     * @ORM\OneToOne(targetEntity="UnitBedroom", inversedBy="unit", cascade={"all"})
     */
    private $bedroom;
    /**
     * @ORM\OneToOne(targetEntity="UnitBathroom", inversedBy="unit", cascade={"all"})
     */
    private $bathroom;
    /**
     * @ORM\OneToOne(targetEntity="UnitLivingroom", inversedBy="unit", cascade={"all"})
     */
    private $livingroom;
    /**
     * @ORM\OneToOne(targetEntity="UnitKitchen", inversedBy="unit", cascade={"all"})
     */
    private $kitchen;
    /**
     * @ORM\OneToOne(targetEntity="UnitAppliances", inversedBy="unit", cascade={"all"})
     */
    private $appliances;
    /**
     * @ORM\OneToMany(targetEntity="UnitPriceExtra", mappedBy="unit", cascade={"all"}, orphanRemoval=TRUE))
     */
    protected $unitPriceExtra;
    /**
     * @ORM\ManyToOne(targetEntity="Accommodation", inversedBy="units")
     */
    private $accommodation;
    /**
     * @ORM\ManyToOne(targetEntity="UnitDetail", inversedBy="unit", cascade={"all"})
     */
    private $details;
    /**
     * @ORM\ManyToMany(targetEntity="UnitView", inversedBy="unit", cascade={"all"})
     */
    private $views;
    /**
     * @ORM\OneToMany(targetEntity="UnitPeriod", mappedBy="unit", cascade={"all"})
     */
    private $periods;
    /**
     * @ORM\OneToMany(targetEntity="Booking", mappedBy="unit", cascade={"persist"})
     */
    private $bookings;
    /**
     * @ORM\OneToMany(targetEntity="UnitGallery", mappedBy="unit", cascade={"all"})
     */
    private $gallery;
    /**
     * @ORM\OneToOne(targetEntity="UnitDescription", inversedBy="unit", cascade={"all"})
     */
    private $description;
    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", mappedBy="wishlistUnits")
     * @ORM\JoinTable(name="wishlists")
     */
    private $wishlistUsers;


    /**
     * Constructor
     */
    public function __construct() {
        $this->unitPriceExtra = new \Doctrine\Common\Collections\ArrayCollection();
        $this->views = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periods = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bookings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gallery = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Unit
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
     * Set categorize
     * @param integer $categorize
     * @return Unit
     */
    public function setCategorize($categorize) {
        $this->categorize = $categorize;
        return $this;
    }

    /**
     * Get categorize
     * @return integer
     */
    public function getCategorize() {
        return $this->categorize;
    }

    /**
     * Set surface
     * @param integer $surface
     * @return Unit
     */
    public function setSurface($surface) {
        $this->surface = $surface;
        return $this;
    }

    /**
     * Get surface
     * @return integer
     */
    public function getSurface() {
        return $this->surface;
    }

    /**
     * Add details
     * @param \AppBundle\Entity\UnitDetail $details
     * @return Unit
     */
    public function addDetail(\AppBundle\Entity\UnitDetail $details) {
        $this->details[] = $details;
        return $this;
    }

    /**
     * Remove details
     * @param \AppBundle\Entity\UnitDetail $details
     */
    public function removeDetail(\AppBundle\Entity\UnitDetail $details) {
        $this->details->removeElement($details);
    }

    /**
     * Get details
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * Set accommodation
     * @param \AppBundle\Entity\Accommodation $accommodation
     * @return Unit
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
     * Set basicPrice
     * @param integer $basicPrice
     * @return Unit
     */
    public function setBasicPrice($basicPrice) {
        $this->basicPrice = $basicPrice;
        return $this;
    }

    /**
     * Get basicPrice
     * @return integer
     */
    public function getBasicPrice() {
        return $this->basicPrice;
    }

    /**
     * Add periods
     * @param \AppBundle\Entity\UnitPeriod $periods
     * @return Unit
     */
    public function addPeriod(\AppBundle\Entity\UnitPeriod $periods) {
        $this->periods[] = $periods;
        return $this;
    }

    /**
     * Remove periods
     * @param \AppBundle\Entity\UnitPeriod $periods
     */
    public function removePeriod(\AppBundle\Entity\UnitPeriod $periods) {
        $this->periods->removeElement($periods);
    }

    /**
     * Get periods
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriods() {
        return $this->periods;
    }

    /**
     * Add bookings
     * @param \AppBundle\Entity\Booking $bookings
     * @return Unit
     */
    public function addBooking(\AppBundle\Entity\Booking $bookings) {
        $this->bookings[] = $bookings;
        return $this;
    }

    /**
     * Remove bookings
     * @param \AppBundle\Entity\Booking $bookings
     */
    public function removeBooking(\AppBundle\Entity\Booking $bookings) {
        $this->bookings->removeElement($bookings);
    }

    /**
     * Get bookings
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookings() {
        return $this->bookings;
    }

    /**
     * Add gallery
     * @param \AppBundle\Entity\UnitGallery $gallery
     * @return Unit
     */
    public function addGallery(\AppBundle\Entity\UnitGallery $gallery) {
        $this->gallery[] = $gallery;
        return $this;
    }

    /**
     * Remove gallery
     * @param \AppBundle\Entity\UnitGallery $gallery
     */
    public function removeGallery(\AppBundle\Entity\UnitGallery $gallery) {
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
     * Set capacity
     * @param integer $capacity
     * @return Unit
     */
    public function setCapacity($capacity) {
        $this->capacity = $capacity;
        return $this;
    }

    /**
     * Get capacity
     * @return integer
     */
    public function getCapacity() {
        return $this->capacity;
    }

    /**
     * Set bedrooms
     * @param integer $bedrooms
     * @return Unit
     */
    public function setBedrooms($bedrooms) {
        $this->bedrooms = $bedrooms;
        return $this;
    }

    /**
     * Get bedrooms
     * @return integer
     */
    public function getBedrooms() {
        return $this->bedrooms;
    }

    /**
     * Set bathrooms
     * @param integer $bathrooms
     * @return Unit
     */
    public function setBathrooms($bathrooms) {
        $this->bathrooms = $bathrooms;
        return $this;
    }

    /**
     * Get bathrooms
     * @return integer
     */
    public function getBathrooms() {
        return $this->bathrooms;
    }

    /**
     * Add views
     * @param \AppBundle\Entity\UnitView $views
     * @return Unit
     */
    public function addView(\AppBundle\Entity\UnitView $views) {
        $this->views[] = $views;
        return $this;
    }

    /**
     * Remove views
     * @param \AppBundle\Entity\UnitView $views
     */
    public function removeView(\AppBundle\Entity\UnitView $views) {
        $this->views->removeElement($views);
    }

    /**
     * Get views
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getViews() {
        return $this->views;
    }

    /**
     * Set bedroom
     * @param \AppBundle\Entity\UnitBedroom $bedroom
     * @return Unit
     */
    public function setBedroom(\AppBundle\Entity\UnitBedroom $bedroom = null) {
        $this->bedroom = $bedroom;
        return $this;
    }

    /**
     * Get bedroom
     * @return \AppBundle\Entity\UnitBedroom
     */
    public function getBedroom() {
        return $this->bedroom;
    }

    /**
     * Set bathroom
     * @param \AppBundle\Entity\UnitBathroom $bathroom
     * @return Unit
     */
    public function setBathroom(\AppBundle\Entity\UnitBathroom $bathroom = null) {
        $this->bathroom = $bathroom;
        return $this;
    }

    /**
     * Get bathroom
     * @return \AppBundle\Entity\UnitBathroom
     */
    public function getBathroom() {
        return $this->bathroom;
    }

    /**
     * Set livingroom
     * @param \AppBundle\Entity\UnitLivingroom $livingroom
     * @return Unit
     */
    public function setLivingroom(\AppBundle\Entity\UnitLivingroom $livingroom = null) {
        $this->livingroom = $livingroom;
        return $this;
    }

    /**
     * Get livingroom
     * @return \AppBundle\Entity\UnitLivingroom
     */
    public function getLivingroom() {
        return $this->livingroom;
    }

    /**
     * Set kitchen
     * @param \AppBundle\Entity\UnitKitchen $kitchen
     * @return Unit
     */
    public function setKitchen(\AppBundle\Entity\UnitKitchen $kitchen = null) {
        $this->kitchen = $kitchen;
        return $this;
    }

    /**
     * Get kitchen
     * @return \AppBundle\Entity\UnitKitchen
     */
    public function getKitchen() {
        return $this->kitchen;
    }

    /**
     * Set unitCategory
     * @param \AppBundle\Entity\UnitCategory $unitCategory
     * @return Unit
     */
    public function setUnitCategory(\AppBundle\Entity\UnitCategory $unitCategory = null) {
        $this->unitCategory = $unitCategory;
        return $this;
    }

    /**
     * Get unitCategory
     * @return \AppBundle\Entity\UnitCategory
     */
    public function getUnitCategory() {
        return $this->unitCategory;
    }

    /**
     * Set minimumStay
     * @param integer $minimumStay
     * @return Unit
     */
    public function setMinimumStay($minimumStay) {
        $this->minimumStay = $minimumStay;
        return $this;
    }

    /**
     * Get minimumStay
     * @return integer
     */
    public function getMinimumStay() {
        return $this->minimumStay;
    }

    /**
     * Set position
     * @param string $position
     * @return Unit
     */
    public function setPosition($position) {
        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     * @return string
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Set details
     * @param \AppBundle\Entity\UnitDetail $details
     * @return Unit
     */
    public function setDetails(\AppBundle\Entity\UnitDetail $details = null) {
        $this->details = $details;

        return $this;
    }

    /**
     * Set appliances
     * @param \AppBundle\Entity\UnitAppliances $appliances
     * @return Unit
     */
    public function setAppliances(\AppBundle\Entity\UnitAppliances $appliances = null) {
        $this->appliances = $appliances;

        return $this;
    }

    /**
     * Get appliances
     * @return \AppBundle\Entity\UnitAppliances
     */
    public function getAppliances() {
        return $this->appliances;
    }

    /**
     * Add unitPriceExtra
     * @param \AppBundle\Entity\UnitPriceExtra $unitPriceExtra
     * @return Unit
     */
    public function addUnitPriceExtra(\AppBundle\Entity\UnitPriceExtra $unitPriceExtra) {
        $this->unitPriceExtra[] = $unitPriceExtra;

        return $this;
    }

    /**
     * Remove unitPriceExtra
     * @param \AppBundle\Entity\UnitPriceExtra $unitPriceExtra
     */
    public function removeUnitPriceExtra(\AppBundle\Entity\UnitPriceExtra $unitPriceExtra) {
        $this->unitPriceExtra->removeElement($unitPriceExtra);
    }

    /**
     * Get unitPriceExtra
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnitPriceExtra() {
        return $this->unitPriceExtra;
    }


    /**
     * Add description
     * @param \AppBundle\Entity\UnitDescription $description
     * @return Unit
     */
    public function addDescription(\AppBundle\Entity\UnitDescription $description) {
        $this->description[] = $description;

        return $this;
    }

    /**
     * Remove description
     * @param \AppBundle\Entity\UnitDescription $description
     */
    public function removeDescription(\AppBundle\Entity\UnitDescription $description) {
        $this->description->removeElement($description);
    }

    /**
     * Get description
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set description
     * @param \AppBundle\Entity\UnitDescription $description
     * @return Unit
     */
    public function setDescription(\AppBundle\Entity\UnitDescription $description = null) {
        $this->description = $description;

        return $this;
    }

    /**
     * Add wishlistUsers
     * @param \UserBundle\Entity\User $wishlistUsers
     * @return Unit
     */
    public function addWishlistUser(\UserBundle\Entity\User $wishlistUsers) {
        $this->wishlistUsers[] = $wishlistUsers;

        return $this;
    }

    /**
     * Remove wishlistUsers
     * @param \UserBundle\Entity\User $wishlistUsers
     */
    public function removeWishlistUser(\UserBundle\Entity\User $wishlistUsers) {
        $this->wishlistUsers->removeElement($wishlistUsers);
    }

    /**
     * Get wishlistUsers
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWishlistUsers() {
        return $this->wishlistUsers;
    }
}
