<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Card\PokerSquare;

/**
 * Test cases for class Game21.
 */
class PokerSquareTest extends TestCase
{
    private PokerSquare $game;

    /**
     * Construct game object
     */
    protected function setUp(): void
    {
        // Create PokerSquare object
        $this->game = new PokerSquare();
        $this->assertInstanceOf("\App\Card\PokerSquare", $this->game);
    }

    /**
     * Test game object has the expected properties.
     */
    public function testActiveCard(): void
    {

        // Check activeCard is a Card object
        $this->assertInstanceOf("\App\Card\Card", $this->game->activeCard);

        // cardGrid is an array
        $this->assertIsArray($this->game->cardGrid);

        // Check cardGrid is 5x5
        $this->assertCount(5, $this->game->cardGrid);
        for ($i = 0; $i < 5; $i++) {
            $this->assertCount(5, $this->game->cardGrid[$i]);
        }
    }

    /**
     * Test place card on grid
     */
    public function testPlaceCard(): void
    {
        // Place card on grid
        $this->assertTrue($this->game->place(4, 4));

        // Check card is placed on grid
        $this->assertInstanceOf("\App\Card\Card", $this->game->cardGrid[4][4]);

        // Place next card on same place on grid
        $this->assertFalse($this->game->place(4, 4));
    }

    /**
     * Test End game
     */
    public function testEndGame(): void
    {
        // Check game has not ended
        $this->assertFalse($this->game->gameEnded());

        // Get score before any cards placed should be 0
        $this->assertEquals(0, $this->game->getTotalPoints());

        // Place 5x5 cards on grid
        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $this->game->place($i, $j);
            }
        }

        // Check game has ended
        $this->assertTrue($this->game->gameEnded());

        // Check activeCard is null
        $this->assertNull($this->game->activeCard);

        // Get score
        $this->assertIsInt($this->game->getTotalPoints());
    }

    /**
     * Test specific hands
     * Royal flush, Straight flush, Four of a kind, Full house
     */
    public function testSpecificHands(): void
    {
        // Place Royal flush on first row
        $this->game->activeCard = new Card('Clubs', '10');
        $this->game->place(0, 0);
        $this->game->activeCard = new Card('Clubs', 'Jack');
        $this->game->place(0, 1);
        $this->game->activeCard = new Card('Clubs', 'Queen');
        $this->game->place(0, 2);
        $this->game->activeCard = new Card('Clubs', 'King');
        $this->game->place(0, 3);
        $this->game->activeCard = new Card('Clubs', 'Ace');
        $this->game->place(0, 4);
        // Get score for Royal flush 100
        $this->assertEquals(100, $this->game->getTotalPoints());

        // Place Straight flush first column
        $this->game->activeCard = new Card('Clubs', '9');
        $this->game->place(1, 0);
        $this->game->activeCard = new Card('Clubs', '8');
        $this->game->place(2, 0);
        $this->game->activeCard = new Card('Clubs', '7');
        $this->game->place(3, 0);
        $this->game->activeCard = new Card('Clubs', '6');
        $this->game->place(4, 0);
        // Get score for Straight flush +75
        $this->assertEquals(175, $this->game->getTotalPoints());

        // Place Four of a kind second row
        $this->game->activeCard = new Card('Spades', '9');
        $this->game->place(1, 1);
        $this->game->activeCard = new Card('Hearts', '9');
        $this->game->place(1, 2);
        $this->game->activeCard = new Card('Diamonds', '9');
        $this->game->place(1, 4);
        $this->game->activeCard = new Card('Diamonds', '2');
        $this->game->place(1, 3);
        // Get score for Four of a kind +50
        $this->assertEquals(225, $this->game->getTotalPoints());

        // Place Full house third row
        $this->game->activeCard = new Card('Spades', '8');
        $this->game->place(2, 1);
        $this->game->activeCard = new Card('Diamonds', '8');
        $this->game->place(2, 2);
        $this->game->activeCard = new Card('Spades', '3');
        $this->game->place(2, 3);
        $this->game->activeCard = new Card('Diamonds', '3');
        $this->game->place(2, 4);
        // Get score for Full house +25
        $this->assertEquals(250, $this->game->getTotalPoints());
    }

    /**
     * Test specific hands
     * Flush, Straight, Three of a kind and two pair
     */
    public function testMoreHands(): void
    {
        // Place Flush on first row
        $this->game->activeCard = new Card('Clubs', '7');
        $this->game->place(0, 0);
        $this->game->activeCard = new Card('Clubs', 'Jack');
        $this->game->place(0, 1);
        $this->game->activeCard = new Card('Clubs', 'Queen');
        $this->game->place(0, 2);
        $this->game->activeCard = new Card('Clubs', 'King');
        $this->game->place(0, 3);
        $this->game->activeCard = new Card('Clubs', 'Ace');
        $this->game->place(0, 4);
        // Get score for Flush 20
        $this->assertEquals(20, $this->game->getTotalPoints());

        // Place Straight first column
        $this->game->activeCard = new Card('Clubs', '9');
        $this->game->place(1, 0);
        $this->game->activeCard = new Card('Clubs', '8');
        $this->game->place(2, 0);
        $this->game->activeCard = new Card('Spades', '5');
        $this->game->place(3, 0);
        $this->game->activeCard = new Card('Clubs', '6');
        $this->game->place(4, 0);
        // Get score for Straight  +15
        $this->assertEquals(35, $this->game->getTotalPoints());

        // Place Three of a kind second row
        $this->game->activeCard = new Card('Spades', '9');
        $this->game->place(1, 1);
        $this->game->activeCard = new Card('Hearts', '3');
        $this->game->place(1, 2);
        $this->game->activeCard = new Card('Diamonds', '9');
        $this->game->place(1, 4);
        $this->game->activeCard = new Card('Diamonds', '2');
        $this->game->place(1, 3);
        // Get score for Three of a kind +19
        $this->assertEquals(45, $this->game->getTotalPoints());

        // Place Two pair third row
        $this->game->activeCard = new Card('Spades', '8');
        $this->game->place(2, 1);
        $this->game->activeCard = new Card('Diamonds', '5');
        $this->game->place(2, 2);
        $this->game->activeCard = new Card('Spades', '3');
        $this->game->place(2, 3);
        $this->game->activeCard = new Card('Diamonds', '3');
        $this->game->place(2, 4);
        // Get score for Full house +5
        $this->assertEquals(50, $this->game->getTotalPoints());
    }

    /**
     * Test imposible cases
     */

    public function testImpossibleCases(): void
    {
        // Remove active card and try to place card on grid
        $this->game->activeCard = null;
        $this->assertFalse($this->game->place(0, 0));

    }

}
