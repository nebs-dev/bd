<?php

namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;

class EmailService {

    protected $em;
    protected $session;
    protected $siteEmail = 'info@best-destination.com';
    protected $superadminEmail = 'nebojsa.stojanovic0@gmail.com';

    function __construct(EntityManager $em, Session $session) {
        $this->em = $em;
        $this->session = $session;
    }


    public function getSiteEmail() {
        return $this->siteEmail;
    }

    public function getSuperadminEmail() {
        return $this->superadminEmail;
    }
}