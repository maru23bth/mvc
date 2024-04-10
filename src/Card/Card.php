<?php

namespace App\Card;

use InvalidArgumentException;

class Card
{
    public const SUITS = [
        "Spades" => [
            "color" => "Black",
            "unicode" => "A"
        ],
        "Hearts" => [
            "color" => "Red",
            "unicode" => "B"
        ],
        "Clubs" => [
            "color" => "Red",
            "unicode" => "D"
        ],
        "Diamonds" => [
            "color" => "Black",
            "unicode" => "C"
        ]
    ];

    public const NUMBERS = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

    public readonly string $suit;
    public readonly string $number;
    public readonly string $color;

    public function __construct($suit = null, $number = null)
    {
        if ($suit) {
            $this->setSuit($suit);
        }
        if ($number) {
            $this->setNumber($number);
        }
    }

    public function setSuit(string $suit)
    {
        if (!array_key_exists($suit, self::SUITS)) {
            throw new InvalidArgumentException("Suit: $suit is not valid");
        }

        $this->suit = $suit;
        $this->color = self::SUITS[$this->suit]['color'];
    }

    public function setNumber(string $number)
    {
        if (!in_array($number, self::NUMBERS)) {
            throw new InvalidArgumentException("Number: $number is not valid");
        }

        $this->number = $number;
    }

    // Unicode map: https://en.wikipedia.org/wiki/Playing_cards_in_Unicode
    public function showCard(): string
    {
        // Begin unicode string
        $result = "&#x1F0";

        // Get correct value for suit
        if (! key_exists($this->suit, self::SUITS)) {
            return $result .= "BF";
        }

        $result .= self::SUITS[$this->suit]['unicode'];
/*        switch ($this->suit) {
            case 'Spades':
                $result .= "A";
                break;
            case 'Hearts':
                $result .= "B";
                break;
            case 'Clubs':
                $result .= "D";
                break;
            case 'Diamonds':
                $result .= "C";
                break;
            default:
                return $result .= "BF";
        }*/

        switch ($this->number) {
            case '10':
                $result .= "A";
                break;
            case 'Jack':
                $result .= "B";
                break;
            case 'Queen':
                $result .= "D";
                break;
            case 'King':
                $result .= "E";
                break;
            case 'Ace':
                $result .= "1";
                break;
            default:
                $result .= $this->number;
                break;
        }

        return $result;
    }

    public function __toString()
    {
        return $this->number . " of " . $this->suit;
    }
}
