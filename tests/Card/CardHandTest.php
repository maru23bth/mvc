<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    public const SUITS = ["Spades", "Hearts", "Clubs", "Diamonds"];
    public const NUMBERS = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCardHand(): void
    {

        // Create CardHand object
        $cardHand = new CardHand();
        $this->assertInstanceOf("\App\Card\CardHand", $cardHand);


        // Loop through all suits and numbers adding to cardHand
        foreach (self::SUITS as $suit) {
            foreach (self::NUMBERS as $number) {
                $card = new Card($suit, $number);
                $cardHand->add($card);
            }
        }

        // Get all cards from hand
        $cards = $cardHand->getcards();
        // Check that hands has 52 cards
        $this->assertCount(52, $cards);

        // Remove all cards from hand
        foreach ($cards as $card) {
            $cardHand->remove($card);
        }

        // Get all cards from hand
        $cards = $cardHand->getcards();
        // Check that hands is empty
        $this->assertCount(0, $cards);

        // Add a specific card two times
        $card = new Card("Spades", "2");
        $cardHand->add($card);
        $cardHand->add($card);
        // Sort the hand, fort testing sort with same cards
        $cardHand->sort();



    }
}
