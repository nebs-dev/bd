<?php

namespace AppBundle\Entity\AdditionalOffer;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="additional_offer_gallery")
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="AdditionalOffer", inversedBy="gallery")
     */
    private $additionalOffer;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;
    /**
     * @Assert\File(maxSize="1M",
     * mimeTypes={"image/jpeg", "image/gif", "image/png"},
     * maxSizeMessage = "The maxmimum allowed file size is 1MB.",
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
//        return __DIR__.'/../../../../web/'.$this->getUploadDir();
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        # Create accommodation's directory if not exists
//        if (!file_exists('upload/gallery/' . $this->accommodation->getId())) {
//            mkdir('upload/gallery/' . $this->accommodation->getId(), 0777, true);
//        }
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/additionalOffers/' . $this->getAdditionalOffer()->getId();
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

        $file_name = rand() . '.' . $this->file->guessExtension();
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
        $this->setPhoto($file_name);
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
     * Set additionalOffer
     * @param \AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer
     * @return Gallery
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
