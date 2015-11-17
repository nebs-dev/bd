<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="banners")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\BannerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Banner {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;
    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="banners")
     */
    private $country;
    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="banners")
     */
    private $region;
    /**
     * @ORM\ManyToOne(targetEntity="Subregion", inversedBy="banners")
     */
    private $subregion;
    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="banners")
     */
    private $city;
    /**
     * @ORM\ManyToMany(targetEntity="Language", inversedBy="banners")
     */
    private $languages;
    /**
     * @Assert\File(maxSize="1M",
     * mimeTypes={"image/jpeg", "image/gif", "image/png"},
     * maxSizeMessage = "The maxmimum allowed file size is 1MB.",
     * mimeTypesMessage = "Only JPEG, PNG and GIF is allowed.")
     */
    protected $file;
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
        $this->languages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        $file = $this->getAbsolutePath();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    ##############
    ### UPLOAD ###
    ##############
    /**
     * Sets file.
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }

    /**
     * Get file.
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    public function getAbsolutePath() {
        return null === $this->photo
            ? null
            : $this->getUploadRootDir() . '/' . $this->photo;
    }

    public function getWebPath() {
        return null === $this->photo
            ? null
            : $this->getUploadDir() . '/' . $this->photo;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
//        return __DIR__.'/../../../../web/'.$this->getUploadDir();
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        # Create accommodation's directory if not exists
//        if (!file_exists('upload/gallery/' . $this->accommodation->getId())) {
//            mkdir('upload/gallery/' . $this->accommodation->getId(), 0777, true);
//        }
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/banners';
    }

    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
//        $file_name = $this->id . '.' . $this->file->guessExtension();
//        $file_name = $this->getFile()->getClientOriginalName();

        $file_name = $this->getId() . $this->getTitle() . '.' . $this->file->guessExtension();
        $file_name = str_replace(" ", "", $file_name);
        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
//            $this->getFile()->getClientOriginalName()
            $file_name
        );
        // set the path property to the filename where you've saved the file
//        $this->path = $this->getFile()->getClientOriginalName();
        $this->photo = $file_name;
//        $this->setUrl('test');
        // clean up the file property as you won't need it anymore
        $this->file = null;
    }


    ###############
    ### /UPLOAD ###
    ###############

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Add languages
     * @param \AppBundle\Entity\Language $languages
     * @return Banner
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
     * @return Banner
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
     * Set photo
     * @param string $photo
     * @return Banner
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
     * Add country
     * @param \AppBundle\Entity\Country $country
     * @return Banner
     */
    public function addCountry(\AppBundle\Entity\Country $country) {
        $this->country[] = $country;

        return $this;
    }

    /**
     * Remove country
     * @param \AppBundle\Entity\Country $country
     */
    public function removeCountry(\AppBundle\Entity\Country $country) {
        $this->country->removeElement($country);
    }

    /**
     * Get country
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Add region
     * @param \AppBundle\Entity\Region $region
     * @return Banner
     */
    public function addRegion(\AppBundle\Entity\Region $region) {
        $this->region[] = $region;

        return $this;
    }

    /**
     * Remove region
     * @param \AppBundle\Entity\Region $region
     */
    public function removeRegion(\AppBundle\Entity\Region $region) {
        $this->region->removeElement($region);
    }

    /**
     * Get region
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Add subregion
     * @param \AppBundle\Entity\Subregion $subregion
     * @return Banner
     */
    public function addSubregion(\AppBundle\Entity\Subregion $subregion) {
        $this->subregion[] = $subregion;

        return $this;
    }

    /**
     * Remove subregion
     * @param \AppBundle\Entity\Subregion $subregion
     */
    public function removeSubregion(\AppBundle\Entity\Subregion $subregion) {
        $this->subregion->removeElement($subregion);
    }

    /**
     * Get subregion
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubregion() {
        return $this->subregion;
    }

    /**
     * Add city
     * @param \AppBundle\Entity\City $city
     * @return Banner
     */
    public function addCity(\AppBundle\Entity\City $city) {
        $this->city[] = $city;

        return $this;
    }

    /**
     * Remove city
     * @param \AppBundle\Entity\City $city
     */
    public function removeCity(\AppBundle\Entity\City $city) {
        $this->city->removeElement($city);
    }

    /**
     * Get city
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set country
     * @param \AppBundle\Entity\Country $country
     * @return Banner
     */
    public function setCountry(\AppBundle\Entity\Country $country = null) {
        $this->country = $country;

        return $this;
    }

    /**
     * Set region
     * @param \AppBundle\Entity\Region $region
     * @return Banner
     */
    public function setRegion(\AppBundle\Entity\Region $region = null) {
        $this->region = $region;

        return $this;
    }

    /**
     * Set subregion
     * @param \AppBundle\Entity\Subregion $subregion
     * @return Banner
     */
    public function setSubregion(\AppBundle\Entity\Subregion $subregion = null) {
        $this->subregion = $subregion;

        return $this;
    }

    /**
     * Set city
     * @param \AppBundle\Entity\City $city
     * @return Banner
     */
    public function setCity(\AppBundle\Entity\City $city = null) {
        $this->city = $city;

        return $this;
    }
}
