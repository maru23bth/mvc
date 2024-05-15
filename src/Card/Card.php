<?php

namespace App\Card;

use InvalidArgumentException;

/**
 * A class for a card.
 * @package App\Card
 */
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

    //public readonly string $suit;
    //public readonly string $number;
    public readonly string $color;

    /**
     * Constructor to create a card.
     * @param string $suit The suit of the card.
     * @param string $number The number of the card.
     */
    public function __construct(public readonly string $suit, public readonly string $number)
    {
        if (!array_key_exists($suit, self::SUITS)) {
            throw new InvalidArgumentException("Suit: $suit is not valid");
        }
        //$this->suit = $suit;
        $this->color = self::SUITS[$this->suit]['color'];

        if (!in_array($number, self::NUMBERS)) {
            throw new InvalidArgumentException("Number: $number is not valid");
        }
        //$this->number = $number;
    }

    // Unicode map: https://en.wikipedia.org/wiki/Playing_cards_in_Unicode

    /**
     * Get the card as a unicode string.
     * @return string
     */
    public function showCard(): string
    {
        // Begin unicode string
        $result = "&#x1F0";

        $result .= self::SUITS[$this->suit]['unicode'];

/*         switch ($this->number) {
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
        } */
        $case = ['10'=>'A', 'Jack'=>'B', 'Queen'=>'D', 'King'=>'E', 'Ace'=>'1'];
        $result .= array_key_exists($this->number, $case) ? $case[$this->number] : $this->number;

        return $result;
    }

    /**
     * Get the value of the card.
     * @return int
     */
    public function getValue(): int
    {
        switch ($this->number) {
            case 'Jack':
                return 11;
            case 'Queen':
                return 12;
            case 'King':
                return 13;
            case 'Ace':
                return 14;
            default:
                return intval($this->number);
        }
    }

    /**
     * Get the name and color of the card as string.
     * @return string
     */
    public function __toString(): string
    {
        return $this->number . " of " . $this->suit;
    }
}
