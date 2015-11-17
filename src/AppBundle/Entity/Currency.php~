<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="currencies")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CurrencyRepository")
 */
class Currency {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string", length=3)
	 */
	private $code;
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $name;
	/**
	 * @ORM\Column(type="decimal", precision=16, scale=10)
	 */
	private $rate;

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set code
	 * @param string $code
	 * @return Currency
	 */
	public function setCode($code) {
		$this->code = $code;
		return $this;
	}

	/**
	 * Get code
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * Set rate
	 * @param string $rate
	 * @return Currency
	 */
	public function setRate($rate) {
		$this->rate = $rate;
		return $this;
	}

	/**
	 * Get rate
	 * @return string
	 */
	public function getRate() {
		return $this->rate;
	}

    /**
     * Set name
     *
     * @param string $name
     * @return Currency
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
