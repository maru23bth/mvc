<?php

namespace App\Controller\Proj;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\HighScorePointsRepository;
use App\Repository\HighScoreUserRepository;
use App\Entity\HighScoreUser;
use App\Entity\HighScorePoints;


use App\Card\PokerSquare;

class ControllerProjApi extends AbstractController
{
    #[Route("/proj/api", name: "proj/api")]
    public function home(): Response
    {
        return $this->render('proj/api.html.twig');
    }

    #[Route("/proj/api/game", name: "proj/api/game")]
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

        $data = ['game' => $game, 'points' => $game->getPoints()];
        $data['points']['total'] = $game->getTotalPoints();

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/proj/api/points", name: "proj/api/points")]
    public function points(SessionInterface $session): Response
    {
        /**
         * @var \App\Card\PokerSquare|null $game
         */
        $game = $session->get('game');

        if (!$game) {
            return new Response('Game not initiated!', 405);
        }

        $data = $game->getPoints();
        $data['total'] = $game->getTotalPoints();

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/proj/api/placecard/{row<[0-4]>}/{col<[0-4]>}", name: "proj/api/placecard", methods: ['POST'])]
    public function placecard(int $row, int $col, SessionInterface $session): Response
    {
        /**
         * @var \App\Card\PokerSquare|null $game
         */
        $game = $session->get('game');

        if (!$game) {
            return new Response('Game not initiated!', 405);
        }

        if (! $game->place($row, $col)) {
            //throw new HttpException(405,  'Invalid placement!');
            return new Response('Invalid placement!', 406);
        }

        $session->set('game', $game);

        return new Response(null);
    }

    #[Route("/proj/api/high-score", name: "proj/api/high-score")]
    public function highScore(HighScorePointsRepository $pointsRepository): Response
    {

        $highScores = $pointsRepository->findBy([], ['score' => 'DESC'], 100);
        $data = [];
        
        foreach ($highScores as $highScore) {
            /** @var HighScoreUser $user */
            $user = $highScore->getUser();
            $data[] = [
                //'id' => $highScore->getId(),
                
                'name' => $user->getName(),
                'score' => $highScore->getScore(),
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

}
