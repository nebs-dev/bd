<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements AdvancedUserInterface, \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $surename;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $address;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $city;
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $title;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $skype;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $google;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $type;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $companyName;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $companyVAT;
    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $zip;
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $phone;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     */
    private $roles;
    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Accommodation", mappedBy="user", cascade={"all"})
     */
    private $accommodations;
    /**
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Country", inversedBy="users")
     */
    private $country;
    /**
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Unit", inversedBy="wishlistUsers", cascade={"all"})
     * @ORM\JoinTable(name="wishlists")
     */
    private $wishlistUnits;
    /**
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Language", inversedBy="users")
     */
    private $languages;
    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Booking", mappedBy="user")
     */
    private $bookings;
    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\PostComment", mappedBy="user")
     */
    private $comments;
    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Review", mappedBy="tourist")
     */
    private $reviews;
    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\AdditionalOffer\AdditionalOffer", mappedBy="host", cascade={"all"})
     */
    private $additionalOffer;
    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="userTo")
     */
    private $messageTo;
    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="userFrom")
     */
    private $messageFrom;
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;
    /**
     * created Time/Date
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;


    public function __construct() {
        // $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
        $this->roles = new ArrayCollection();
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
     * Set hash
     * @ORM\PrePersist
     */
    public function setHash() {
        $newHash = password_hash(microtime() . uniqid(), PASSWORD_BCRYPT, array('cost' => 12));

        $this->hash = str_replace('/', '', $newHash);

        return $this;
    }

    public function getHash() {
        return $this->hash;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->isActive;
    }

    /**
     * @ORM\PrePersist
     */
    public function encryptPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT, array('cost' => 12));
    }

    public function getFullName() {
        return $this->name . ' ' . $this->surename;
    }

    /**
     * @return string
     */
    public function __toString() {
        return strval($this->id);
    }

    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt() {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * Set password
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * Set email
     * @param string $email
     * @return User
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
     * Set isActive
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get isActive
     * @return boolean
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * @inheritDoc
     */
    public function getRoles() {
        return $this->roles->toArray();
    }

    /**
     * @param Collection $roles
     */
    public function setRoles(\UserBundle\Entity\Role $roles) {
        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    /**
     * Add roles
     * @param \UserBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\UserBundle\Entity\Role $roles) {
        $this->roles[] = $roles;
        return $this;
    }

    /**
     * Remove roles
     * @param \UserBundle\Entity\Role $roles
     */
    public function removeRole(\UserBundle\Entity\Role $roles) {
        $this->roles->removeElement($roles);
    }

    /**
     * Set name
     * @param string $name
     * @return User
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
     * Set surename
     * @param string $surename
     * @return User
     */
    public function setSurename($surename) {
        $this->surename = $surename;
        return $this;
    }

    /**
     * Get surename
     * @return string
     */
    public function getSurename() {
        return $this->surename;
    }

    /**
     * Set address
     * @param string $address
     * @return User
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
     * Set phone
     * @param string $phone
     * @return User
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
     * Set photo
     * @param string $photo
     * @return User
     */
    public function setPhoto($photo) {
        $this->photo = $photo;
        return $this;
    }

    /**
     * Get photo
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Add accommodations
     * @param \AppBundle\Entity\Accommodation $accommodations
     * @return User
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

    /**
     * Add bookings
     * @param \AppBundle\Entity\Booking $bookings
     * @return User
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
     * Set type
     * @param string $type
     * @return User
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set companyName
     * @param string $companyName
     * @return User
     */
    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * Get companyName
     * @return string
     */
    public function getCompanyName() {
        return $this->companyName;
    }

    /**
     * Set companyVAT
     * @param string $companyVAT
     * @return User
     */
    public function setCompanyVAT($companyVAT) {
        $this->companyVAT = $companyVAT;
        return $this;
    }

    /**
     * Get companyVAT
     * @return string
     */
    public function getCompanyVAT() {
        return $this->companyVAT;
    }

    /**
     * Set zip
     * @param integer $zip
     * @return User
     */
    public function setZip($zip) {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Get zip
     * @return integer
     */
    public function getZip() {
        return $this->zip;
    }

    /**
     * Add languages
     * @param \AppBundle\Entity\Language $languages
     * @return User
     */
    public function addLanguage(\AppBundle\Entity\Language $languages) {
        $this->languages[] = $languages;
        return $this;
    }

    /**
     * Remove languages
     * @param \AppBundle\Entity\Language $languages
     */
    public function removeLanguage(\AppBundle\Entity\Language $languages) {
        $this->languages->removeElement($languages);
    }

    /**
     * Get languages
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguages() {
        return $this->languages;
    }

    /**
     * Set title
     * @param string $title
     * @return User
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
     * Set skype
     * @param string $skype
     * @return User
     */
    public function setSkype($skype) {
        $this->skype = $skype;
        return $this;
    }

    /**
     * Get skype
     * @return string
     */
    public function getSkype() {
        return $this->skype;
    }

    /**
     * Set google
     * @param string $google
     * @return User
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
     * Set country
     * @param \AppBundle\Entity\Country $country
     * @return User
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
     * Set city
     * @param string $city
     * @return User
     */
    public function setCity($city) {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Add wishlistUnits
     * @param \AppBundle\Entity\Unit $wishlistUnits
     * @return User
     */
    public function addWishlistUnit(\AppBundle\Entity\Unit $wishlistUnits) {
        $this->wishlistUnits[] = $wishlistUnits;

        return $this;
    }

    /**
     * Remove wishlistUnits
     * @param \AppBundle\Entity\Unit $wishlistUnits
     */
    public function removeWishlistUnit(\AppBundle\Entity\Unit $wishlistUnits) {
        $this->wishlistUnits->removeElement($wishlistUnits);
    }

    /**
     * Get wishlistUnits
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWishlistUnits() {
        return $this->wishlistUnits;
    }

    /**
     * Add messageTo
     * @param \UserBundle\Entity\Message $messageTo
     * @return User
     */
    public function addMessageTo(\UserBundle\Entity\Message $messageTo) {
        $this->messageTo[] = $messageTo;

        return $this;
    }

    /**
     * Remove messageTo
     * @param \UserBundle\Entity\Message $messageTo
     */
    public function removeMessageTo(\UserBundle\Entity\Message $messageTo) {
        $this->messageTo->removeElement($messageTo);
    }

    /**
     * Get messageTo
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessageTo() {
        return $this->messageTo;
    }

    /**
     * Add messageFrom
     * @param \UserBundle\Entity\Message $messageFrom
     * @return User
     */
    public function addMessageFrom(\UserBundle\Entity\Message $messageFrom) {
        $this->messageFrom[] = $messageFrom;

        return $this;
    }

    /**
     * Remove messageFrom
     * @param \UserBundle\Entity\Message $messageFrom
     */
    public function removeMessageFrom(\UserBundle\Entity\Message $messageFrom) {
        $this->messageFrom->removeElement($messageFrom);
    }

    /**
     * Get messageFrom
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessageFrom() {
        return $this->messageFrom;
    }

    /**
     * Add comments
     * @param \AppBundle\Entity\PostComment $comments
     * @return User
     */
    public function addComment(\AppBundle\Entity\PostComment $comments) {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     * @param \AppBundle\Entity\PostComment $comments
     */
    public function removeComment(\AppBundle\Entity\PostComment $comments) {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Set note
     * @param string $note
     * @return User
     */
    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     * @return string
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * Add reviews
     * @param \AppBundle\Entity\Review $reviews
     * @return User
     */
    public function addReview(\AppBundle\Entity\Review $reviews) {
        $this->reviews[] = $reviews;

        return $this;
    }

    /**
     * Remove reviews
     * @param \AppBundle\Entity\Review $reviews
     */
    public function removeReview(\AppBundle\Entity\Review $reviews) {
        $this->reviews->removeElement($reviews);
    }

    /**
     * Get reviews
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReviews() {
        return $this->reviews;
    }


    /**
     * Set additionalOffer
     *
     * @param \AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer
     * @return User
     */
    public function setAdditionalOffer(\AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer = null) {
        $this->additionalOffer = $additionalOffer;

        return $this;
    }

    /**
     * Get additionalOffer
     *
     * @return \AppBundle\Entity\AdditionalOffer\AdditionalOffer
     */
    public function getAdditionalOffer() {
        return $this->additionalOffer;
    }
}
