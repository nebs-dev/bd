<?php

namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class CurrencyService {
	protected $em;
	protected $session;

	function __construct(EntityManager $em, Session $session) {
		$this->em      = $em;
		$this->session = $session;
		$currencySession = $session->get('currency');
		if(empty($currencySession)) {
			$currency = 'EUR';
		} else {
			$currency = $currencySession;
		}
		$session->set('currency', $currency);
	}

	/**
	 * Get all currencies
	 * @return array
	 */
	public function getCurrencies() {
		$currencies = $this->em->getRepository('AppBundle:Currency')->findAll();
		return $currencies;
	}

	/**
	 * Calculate from euro
	 * @param      $price
	 * @param null $code
	 * @return mixed
	 */
	public function fromEuro($price, $code = null) {
		if(is_null($code))
			$code = $this->session->get('currency');

		$currency = $this->em->getRepository('AppBundle:Currency')->getByCode($code);

		$newPrice = $price * $currency->getRate();
//		return round($newPrice, 2);
		return $newPrice;
	}

	/**
	 * Calculate to euro
	 * @param      $price
	 * @param null $code
	 * @return float
	 */
	public function toEuro($price, $code = null) {
		if(is_null($code))
			$code = $this->session->get('currency');

		$currency = $this->em->getRepository('AppBundle:Currency')->getByCode($code);

		$newPrice = $price / $currency->getRate();
//        return round($newPrice, 2);
        return $newPrice;
	}

	/**
	 * Set currency
	 * @param $code
	 */
	public function setCurrency($code) {
		$this->session->set('currency', $code);
	}
}