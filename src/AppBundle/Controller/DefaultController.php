<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/qui-sommes-nous", name="qui_sommes_nous")
     */
    public function whoweareAction()
    {
        return $this->render('default/whoweare.html.twig');
    }

    /**
    * @Route("/contact", name="contact")
    */
    public function contactAction()
    {
        return $this->render('default/contact.html.twig');
    }
}
