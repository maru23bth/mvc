<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerTwig extends AbstractController
{
    #[Route("/", name: "index")]
    public function home(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/lucky", name: "lucky_number")]
    public function lucky(): Response
    {
        $number = random_int(0, 100);

        $fortuneCookieSayings = [
            "A closed mouth gathers no feet.",
            "He who throws dirt is losing ground.",
            "Borrow money from a pessimist. They don't expect it back.",
            "Life is what happens to you while you are busy making other plans.",
            "Help! I'm being held prisoner in a fortune cookie factory.",
        ];

        return $this->render('lucky.html.twig',
        [
            "number" => $number,
            "saying" => $fortuneCookieSayings[array_rand($fortuneCookieSayings)]
        ]);
    }
}
