<?php

namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class MessageService {

    protected $em;
    protected $session;

    function __construct(EntityManager $em, Session $session) {
        $this->em = $em;
        $this->session = $session;
    }


    public function newNum($user) {
        return $this->em->getRepository('UserBundle:Message')->getNewMessagesNum($user);
    }


    public function newMessages($user, $limit = 5) {
        return $this->em->getRepository('UserBundle:Message')->getNewMessages($user, $limit);
    }

}