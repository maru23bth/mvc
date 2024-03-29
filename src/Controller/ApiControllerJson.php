<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiControllerJson extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('api.html.twig');
    }

    #[Route("/api/quote ")]
    public function quote(): Response
    {
        $fortuneCookieSayings = [
            "A closed mouth gathers no feet.",
            "He who throws dirt is losing ground.",
            "Borrow money from a pessimist. They don't expect it back.",
            "Life is what happens to you while you are busy making other plans.",
            "Help! I'm being held prisoner in a fortune cookie factory.",
        ];

        $data = [
            'quote' => $fortuneCookieSayings[array_rand($fortuneCookieSayings)],
            'date' => date('Y-m-d'),
            'timestamp' => time(),
        ];

        //return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;        
    }    
}
