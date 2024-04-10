<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ControllerSession extends AbstractController
{
    #[Route("/session", name: "session")]
    public function session(): Response
    {
        #$session->set('test_key', 'test value');
        return $this->render('session.html.twig');
    }

    #[Route("/session/delete", name: "session_delete")]
    public function sessionDelete(SessionInterface $session): Response
    {
        $session->clear();

        $this->addFlash(
            'notice',
            'Session cleard!'
        );
        return $this->redirectToRoute('session');
    }

}
