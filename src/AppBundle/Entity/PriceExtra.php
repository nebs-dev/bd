<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="price_extra")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PriceExtraRepository")
 */
class PriceExtra {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="unitPriceExtra", mappedBy="priceExtra", cascade={"all"}, orphanRemoval=TRUE))
     */
    private $unitPriceExtra;

    /**
     * Constructor
     */
    public function __construct() {
        $this->unitPriceExtra = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PriceExtra
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
     * Add unitPriceExtra
     * @param \AppBundle\Entity\unitPriceExtra $unitPriceExtra
     * @return PriceExtra
     */
    public function addUnitPriceExtra(\AppBundle\Entity\unitPriceExtra $unitPriceExtra) {
//        $this->unitPriceExtra[] = $unitPriceExtra;
        if (!$this->unitPriceExtra->contains($unitPriceExtra)) {
            $this->unitPriceExtra->add($unitPriceExtra);
            $unitPriceExtra->setPriceExtra($this);
        }

        return $this;
    }

    /**
     * Remove unitPriceExtra
     * @param \AppBundle\Entity\unitPriceExtra $unitPriceExtra
     */
    public function removeUnitPriceExtra(\AppBundle\Entity\unitPriceExtra $unitPriceExtra) {
//        $this->unitPriceExtra->removeElement($unitPriceExtra);

        if ($this->unitPriceExtra->contains($unitPriceExtra)) {
            $this->unitPriceExtra->removeElement($unitPriceExtra);
            $unitPriceExtra->setPerson(null);
        }
    }

    /**
     * Get Units
     * @return array
     */
    public function getUnits() {
        return array_map(
            function ($unitPriceExtra) {
                return $unitPriceExtra->getUnit();
            },
            $this->unitPriceExtra->toArray()
        );
    }

    /**
     * Get unitPriceExtra
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnitPriceExtra() {
        return $this->unitPriceExtra;
    }
}
