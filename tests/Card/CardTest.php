<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    public const SUITS = ["Spades", "Hearts", "Clubs", "Diamonds"];
    public const NUMBERS = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

    /**
     * Construct invalid object and verify that trigger exception.
     */
    public function testCreateInvalidSuite()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Card("Invalid", "2");
    }

    /**
     * Construct invalid object and verify that trigger exception.
     */
    public function testCreateInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Card("Spades", "Invalid");
    }

    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCard()
    {

        // Loop through all suits and numbers
        foreach (self::SUITS as $suit) {
            foreach (self::NUMBERS as $number) {
                $card = new Card($suit, $number);
                $this->assertInstanceOf("\App\Card\Card", $card);

                // Test properties
                $this->assertEquals($suit, $card->suit);
                $this->assertEquals($number, $card->number);
                $this->assertNotEmpty($card->color);

                // Test getValue()
                if ($number === "Jack") {
                    $this->assertEquals(11, $card->getValue());
                } elseif ($number === "Queen") {
                    $this->assertEquals(12, $card->getValue());
                } elseif ($number === "King") {
                    $this->assertEquals(13, $card->getValue());
                } elseif ($number === "Ace") {
                    $this->assertEquals(14, $card->getValue());
                } else {
                    $this->assertEquals((int) $number, $card->getValue());
                }

                // Test showCard()
                $res = $card->showCard();
                $this->assertNotEmpty($res);

                // Test __tostring()
                $res = (string) $card;
                $this->assertNotEmpty($res);
            }
        }
    }
}
