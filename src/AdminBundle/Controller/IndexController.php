<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller {


    public function indexAction() {
        return $this->render('AdminBundle::index.html.twig');
    }
}
