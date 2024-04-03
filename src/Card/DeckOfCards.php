<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardCollection;

class DeckOfCards extends CardCollection
{
    public function __construct()
    {
        foreach (array_keys(Card::SUITS) as $suit) {
            foreach (Card::NUMBERS as $number) {
                $this->cards[] = new Card($suit, $number);
            }
        }
    }
}
