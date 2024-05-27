<?php

namespace App\Controller\Proj;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Card\PokerSquare;

class ControllerProj extends AbstractController
{
    #[Route("/proj/", name: "proj/index")]
    public function home(): Response
    {
        return $this->render('proj/index.html.twig');
    }

    #[Route("/proj/about", name: "proj/about")]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route("/proj/game", name: "proj/game")]
    public function game(Request $request, SessionInterface $session): Response
    {
        /**
         * @var \App\Card\PokerSquare $game
         */
        $game = $session->get('game', new PokerSquare());

        // If ?reset in query string, start new game
        if ($request->query->has('reset')) {
            $game = new PokerSquare();
        }

        $session->set('game', $game);

        return $this->render('proj/game.html.twig', ['game' => $game, 'points' => $game->getPoints()]);
    }

    #[Route("/proj/placecard/{row<[0-4]>}/{col<[0-4]>}", name: "proj/placecard")]
    public function placecard(int $row, int $col, SessionInterface $session): Response
    {
        /**
         * @var \App\Card\PokerSquare|null $game
         */
        $game = $session->get('game');
        if (!$game) {
            $this->addFlash('notice', 'Game not initiated!');
            return $this->redirectToRoute('proj/game');
        }

        if (! $game->place($row, $col)) {
            $this->addFlash('notice', 'Invalid placement!');
            return $this->redirectToRoute('proj/game');
        }

        $session->set('game', $game);

        return $this->redirectToRoute('proj/game');
    }
}
