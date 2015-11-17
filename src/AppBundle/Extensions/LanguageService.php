<?php

namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class LanguageService {
    protected $em;
    protected $session;

    function __construct(EntityManager $em, Session $session) {
        $this->em      = $em;
        $this->session = $session;

        $langSession = $session->get('lang');
        if(empty($langSession)) {
            $lang = 'en';
        } else {
            $lang = $langSession;
        }
        $session->set('lang', $lang);
    }

    /**
     * Get language by code
     * @param $code
     * @return array
     */
    public function getLanguage($code) {
        $langObj = $this->em->getRepository('AppBundle:Language')->findOneBy(array('code' => $code));
        return $langObj;
    }

    /**
     * Get all languages
     * @return array
     */
    public function getLanguages() {
        $languages = $this->em->getRepository('AppBundle:Language')->findAll();
        return $languages;
    }

    /**
     * Set language
     * @param $code
     */
    public function setLanguage($code) {
        $this->session->set('lang', $code);
    }
}