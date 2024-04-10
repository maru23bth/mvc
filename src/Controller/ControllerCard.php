<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Card\DeckOfCards;
use App\Card\CardHand;

class ControllerCard extends AbstractController
{
    #[Route("/card", name: "card/index")]
    public function index(): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $cards = [];
        for ($i = 0; $i < 52; $i++) {
            //$cards[] = $deck->draw();
        }

        $cards[] = $deck->draw();
        $cards[] = $deck->draw();
        $cards[] = $deck->draw();

        $serializeDeck = serialize($deck);

        return $this->render('card/index.html.twig', ['deck' => $deck->cards, 'hand' => $cards, 'serializeDeck' => $serializeDeck]);
    }

    #[Route("card/deck", name: "card/deck")]
    public function deck(SessionInterface $session): Response
    {
        /**
         * @var \App\Card\DeckOfCards $deck
         */
        $deck = $session->get('deck', new DeckOfCards());
        $session->set('deck', $deck);

        $deck->shuffle();
        $deck->sort();

        return $this->render('card/deck.html.twig', ['deck' => $deck->cards]);
    }

    #[Route("/card/deck/shuffle", name: "card/deck/shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        return $this->render('card/deck.html.twig', ['deck' => $deck->cards]);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "card/deck/draw", defaults: ["number" => 1])]
    public function draw(int $number, SessionInterface $session): Response
    {
        /**
         * @var \App\Card\DeckOfCards $deck
         */
        $deck = $session->get('deck', new DeckOfCards());
        /**
         * @var \App\Card\CardHand $hand
         */
        $hand = $session->get('hand', new CardHand());
        $cards = [];

        for ($i = 0; $i < $number; $i++) {
            $card = $deck->draw();
            if ($card) {
                $cards[] = $card;
                $hand->add($card);
            }
        }

        $session->set('deck', $deck);
        $session->set('hand', $hand);

        return $this->render('card/draw.html.twig', ['deck' => $deck->cards, 'cards' => $cards]);
    }

    #[Route("/card/deck/deal/{players<\d+>}/{cards<\d+>}", name: "card/deck/deal")]
    public function deal(int $players, int $cards, SessionInterface $session): Response
    {
        /**
         * @var \App\Card\DeckOfCards $deck
         */
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

        return $this->render('card/deal.html.twig', ['deck' => $deck->cards, 'hands' => $hands]);
    }
}
