<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Card\Game21;

class ControllerGame extends AbstractController
{
    #[Route("/game/", name: "game/index")]
    public function game(): Response
    {
        return $this->render('game/index.html.twig');
    }

    #[Route("/game/doc", name: "game/doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route("/game/play", name: "game/play", methods: ['GET'])]
    public function play(SessionInterface $session): Response
    {
        /**
         * @var \App\Card\Game21 $game
         */
        $game = $session->get('game', new Game21());
        $session->set('game', $game);

        $winner = $game->winner();
        if ($winner !== false) {
            $winner = $winner ? 'Spelare' : 'Bank';
        }

        return $this->render('game/play.html.twig', ['game' => $game, 'winner' => $winner]);
    }

    #[Route("/game/play", methods: ['POST'])]
    public function playPost(Request $request, SessionInterface $session): Response
    {

        /**
         * @var \App\Card\Game21|false $game
         */
        $game = $session->get('game', false);
        if (!$game) {
            $this->addFlash('notice', 'Game not initiated!');
            return $this->redirectToRoute('game/play');
        }

        $play = $request->get('play', false);
        if (!$play && in_array($play, ['draw', 'stop'])) {
            $this->addFlash('notice', 'You can\'t trick me!');
            return $this->redirectToRoute('game/play');
        }

        switch ($play) {
            case 'draw':
                if (! $game->playerDraw()) {
                    $this->addFlash('notice', 'Kunde inte ta ytterligare kort!');
                }
                break;
            case 'stop':
                $game->playerStop();
                $this->addFlash('notice', 'Stop!');
                break;
            case 'reset':
                $game = new Game21();
                $this->addFlash('notice', 'Reset!');
                break;
        }

        $session->set('game', $game);

        return $this->redirectToRoute('game/play');
    }

    #[Route("/api/game ", methods: ['GET'])]
    public function apiGame(SessionInterface $session): Response
    {
        /**
         * @var \App\Card\Game21|false $game
         */
        $game = $session->get('game', false);
        if (!$game) {
            return new JsonResponse('Game not initiated!');
        }

        $data = [
            'player' => [
                'best' => $game->getBestHandValue($game->player),
                'posible' => $game->getHandValues($game->player),
            ],
            'bank' => [
                'best' => $game->getBestHandValue($game->bank),
                'posible' => $game->getHandValues($game->bank),
            ],
            'winner' => $game->winner(),
        ];

        if ($data['winner'] === false) {
            $data['winner'] = 'Unknown';
        }
        switch ($data['winner']) {
            case 0:
                $data['winner'] = 'Bank';
                break;
            case 1:
                $data['winner'] = 'Player';
                break;
            default:
                $data['winner'] = 'Unknown';
                break;
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
