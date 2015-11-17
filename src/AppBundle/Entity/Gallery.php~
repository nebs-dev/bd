<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="gallery")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\GalleryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Gallery {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Accommodation", inversedBy="gallery")
     */
    private $accommodation;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $photo;
    /**
     * @ORM\Column(type="boolean")
     */
    private $featuredImage;
    /**
     * @Assert\File(maxSize="3M",
     * mimeTypes={"image/jpeg", "image/gif", "image/png"},
     * maxSizeMessage = "The maxmimum allowed file size is 3MB.",
     * mimeTypesMessage = "Only JPEG, PNG and GIF is allowed.")
     */
    protected $file;

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
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/gallery/' . $this->accommodation->getId() . '/';
    }

    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
//        $file_name = $this->id . '.' . $this->file->guessExtension();
        $file_name = sha1(microtime() . $this->accommodation->getId()) .'.'. $this->file->guessExtension();
        $file_name = str_replace(" ", "", $file_name);
        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $file_name
        );
        // set the path property to the filename where you've saved the file
        $this->photo = $file_name;
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
     * Set accommodation
     * @param \AppBundle\Entity\Accommodation $accommodation
     * @return Gallery
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
     * Set photo
     * @param string $photo
     * @return Gallery
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
     * Set featuredImage
     * @param boolean $featuredImage
     * @return Gallery
     */
    public function setFeaturedImage($featuredImage) {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    /**
     * Get featuredImage
     * @return boolean
     */
    public function getFeaturedImage() {
        return $this->featuredImage;
    }
}
