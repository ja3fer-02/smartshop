<?php

namespace ModelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ModelBundle:Default:index.html.twig');
    }

    /**
     * @Route("/reset/{token}", name="resetPassword")
     */
    public function openResetPasswordAction($token)
    {
        return new RedirectResponse("smarty://smarty/resetpassword/$token");
    }
}
