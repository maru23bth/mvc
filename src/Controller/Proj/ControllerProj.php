<?php

namespace App\Controller\Proj;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\HighScorePointsRepository;
use App\Repository\HighScoreUserRepository;
use App\Entity\HighScoreUser;
use App\Entity\HighScorePoints;

use App\Card\PokerSquare;
use RuntimeException;

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

    /**
     * Show High score
     */
    #[Route("/proj/high-score", name: "proj/high-score", methods: ['GET'])]
    public function highScore(HighScorePointsRepository $pointsRepository): Response
    {
        $highScores = $pointsRepository->findBy([], ['score' => 'DESC'], 100);
        $data = [];
        foreach ($highScores as $highScore) {
            $data[] = [
                //'id' => $highScore->getId(),
                'name' => $highScore->getUser()->getName(),
                'score' => $highScore->getScore(),
            ];
        }

        return $this->render('proj/high-score.html.twig', ['highScores' => $data]);  
    }

    /**
     * Save new high score entry
     */
    #[Route("/proj/high-score-post", name: "proj/high-score-post", methods: ['POST'])]
     public function highScorePost(Request $request, SessionInterface $session, ManagerRegistry $doctrine, HighScoreUserRepository $userRepository, HighScorePointsRepository $pointsRepository): Response
    {

        // Get posted name
        $name = $request->request->get('name');
        if (!$name) {
            $this->addFlash('notice', 'Name is required!');
            return $this->redirectToRoute('proj/high-score');
        }

        // Check that game is played and points are available
        $game = $session->get('game');
        if (!$game) {
            $this->addFlash('notice', 'Game not initiated!');
            return $this->redirectToRoute('proj/high-score');
        }
        $points = $game->getTotalPoints();
        if ($points <= 0 || !$game->gameEnded()) {
            $this->addFlash('notice', 'No points to save!');
            return $this->redirectToRoute('proj/high-score');
        }
        

        $entityManager = $doctrine->getManager();

        // Create new user if not exists
        $user = $userRepository->findOneBy(['name' => $name]);
        if (!$user) {
            $user = new HighScoreUser();
            $user->setName($name);
        }

        // Save high score
        $highScore = new HighScorePoints();
        $highScore->setScore($points);
        $user->addScore($highScore);
        $entityManager->persist($user);
        $entityManager->persist($highScore);
        $entityManager->flush();

        $this->addFlash('notice', "Sparade $name med $points poäng!");

        // Remove game
        $session->remove('game');

        return $this->redirectToRoute('proj/high-score');
    }
}
