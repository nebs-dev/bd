<?php

namespace AppBundle\Entity\AdditionalOffer;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="additional_offer_details")
 * @ORM\Entity()
 */
class Detail {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AdditionalOffer", mappedBy="details")
     */
    private $additionalOffer;

    /**
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @ORM\Column(type="string")
     */
    private $address;
    /**
     * @ORM\Column(type="decimal", precision=8, scale=6, nullable=true)
     */
    private $x;
    /**
     * @ORM\Column(type="decimal", precision=8, scale=6, nullable=true)
     */
    private $y;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $web;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $facebook;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $google;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $twitter;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video;

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     * @param string $title
     * @return Detail
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set address
     * @param string $address
     * @return Detail
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set x
     * @param string $x
     * @return Detail
     */
    public function setX($x) {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     * @return string
     */
    public function getX() {
        return $this->x;
    }

    /**
     * Set y
     * @param string $y
     * @return Detail
     */
    public function setY($y) {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     * @return string
     */
    public function getY() {
        return $this->y;
    }

    /**
     * Set email
     * @param string $email
     * @return Detail
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set phone
     * @param string $phone
     * @return Detail
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set web
     * @param string $web
     * @return Detail
     */
    public function setWeb($web) {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     * @return string
     */
    public function getWeb() {
        return $this->web;
    }

    /**
     * Set facebook
     * @param string $facebook
     * @return Detail
     */
    public function setFacebook($facebook) {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     * @return string
     */
    public function getFacebook() {
        return $this->facebook;
    }

    /**
     * Set google
     * @param string $google
     * @return Detail
     */
    public function setGoogle($google) {
        $this->google = $google;

        return $this;
    }

    /**
     * Get google
     * @return string
     */
    public function getGoogle() {
        return $this->google;
    }

    /**
     * Set twitter
     * @param string $twitter
     * @return Detail
     */
    public function setTwitter($twitter) {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     * @return string
     */
    public function getTwitter() {
        return $this->twitter;
    }

    /**
     * Set video
     * @param string $video
     * @return Detail
     */
    public function setVideo($video) {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     * @return string
     */
    public function getVideo() {
        return $this->video;
    }

    /**
     * Set additionalOffer
     * @param \AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer
     * @return Detail
     */
    public function setAdditionalOffer(\AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer = null) {
        $this->additionalOffer = $additionalOffer;

        return $this;
    }

    /**
     * Get additionalOffer
     * @return \AppBundle\Entity\AdditionalOffer\AdditionalOffer
     */
    public function getAdditionalOffer() {
        return $this->additionalOffer;
    }
}
