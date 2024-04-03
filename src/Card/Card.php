<?php

namespace App\Card;

use InvalidArgumentException;

class Card
{
    public const SUITS = [
        "Spades" => [
            "color" => "Black"
        ],
        "Hearts" => [
            "color" => "Red"
        ],
        "Clubs" => [
            "color" => "Red"
        ],
        "Diamonds" => [
            "color" => "Black"
        ]
    ];

    public const NUMBERS = ['2', '3', '4', '5', '6', '7', '8', '9', '10','Jack', 'Queen', 'King', 'Ace'];

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
        if (array_key_exists($suit, self::SUITS)) {
            $this->suit = $suit;
            $this->color = self::SUITS[$this->suit]['color'];
        } else {
            throw new InvalidArgumentException("Suit: $suit is not valid");
        }
    }

    public function setNumber(string $number)
    {
        if (in_array($number, self::NUMBERS)) {
            $this->number = $number;
        } else {
            throw new InvalidArgumentException("Number: $number is not valid");
        }
    }

    // Unicode map: https://en.wikipedia.org/wiki/Playing_cards_in_Unicode
    public function showCard(): string
    {
        // Begin unicode string
        $result = "&#x1F0";

        // Get correct value for suit
        if ($this->suit == 'Spades') {
            $result .= "A";
        } elseif ($this->suit == 'Hearts') {
            $result .= "B";
        } elseif ($this->suit == 'Clubs') {
            $result .= "D";
        } elseif ($this->suit == 'Diamonds') {
            $result .= "C";
        } else {  // Joker - return before checking value
            return $result .= "BF";
        }


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
