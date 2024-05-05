<?php

namespace App\Card;

use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{

    const SUITS = ["Spades", "Hearts", "Clubs", "Diamonds"];
    const NUMBERS = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateDeckOfCards()
    {
        // Create DeckOfCards object
        $deckOfCards = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deckOfCards);

        // Shuffle the deck
        $deckOfCards->shuffle();

        // Sort the deck
        $deckOfCards->sort();

        // Get all cards from deck
        $cards = $deckOfCards->cards;
        // Check that deck has 52 cards
        $this->assertCount(52, $cards);

        // Check that card exists
        $this->assertTrue($deckOfCards->exists($cards[0]));

        // Draw a specific card
        $card = $deckOfCards->draw($cards[0]);
        $this->assertInstanceOf("\App\Card\Card", $card);

        // Check that card is removed from deck
        $this->assertFalse($deckOfCards->exists($cards[0]));

        // Draw a random card
        $card = $deckOfCards->draw();
        $this->assertInstanceOf("\App\Card\Card", $card);

        // Try to draw the same card again
        $this->assertFalse($deckOfCards->draw($card));

        // Get deck as string
        $deck = (string) $deckOfCards;
        $this->assertStringContainsString('cards', $deck);
        
        // Draw all remaining cards
        while ($card = $deckOfCards->draw()) {
            $this->assertInstanceOf("\App\Card\Card", $card);
        }
    }

    /**
     * Contruct object and test to get invalid property.
     * @expectedException Error
     */
    public function testGetInvalidProperty()
    {
        $deckOfCards = new DeckOfCards();
        $this->expectNotice();
        $deckOfCards->invalid;
    }
}