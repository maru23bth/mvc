<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Card\DeckOfCards;
use App\Card\CardHand;

class ControllerApiJsonCard extends AbstractController
{
    #[Route("/api/deck ", methods: ['GET'])]
    public function deck(SessionInterface $session): Response
    {
        $deck = $session->get('deck', new DeckOfCards());
        $session->set('deck', $deck);

        $deck->shuffle();
        $deck->sort();

        $data = [
            'deck' => $deck->cards,
            'date' => date('Y-m-d'),
            'timestamp' => time(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", methods: ['POST'])]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $data = [
            'deck' => $deck->cards,
            'date' => date('Y-m-d'),
            'timestamp' => time(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/deal/{players<\d+>}/{cards<\d+>}", methods: ['POST'])]
    public function deal(int $players, int $cards, SessionInterface $session): Response
    {
        $deck = $session->get('deck', new DeckOfCards());
        $hands = [];

        // Loop $players and create hands
        for ($i = 0; $i < $players; $i++) {
            $hands[] = new CardHand();
        }

        // Loop cards and draw card for each hand
        for ($i = 0; $i < $cards; $i++) {
            foreach ($hands as $hand) {
                $card = $deck->draw();
                if ($card) {
                    $hand->add($card);
                }
            }
        }

        $session->set('deck', $deck);
        $session->set('hands', $hands);

        $data = [
            'cards' => $cards,
            'hands' => array_map(fn ($hand) => $hand->cards, $hands),
            'cards_left_in_the_deck' => count($deck->cards),
            'date' => date('Y-m-d'),
            'timestamp' => time(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
