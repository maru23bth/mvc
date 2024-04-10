<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardCollection;

class CardHand extends CardCollection
{
    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function remove(Card $card): Card|false
    {
        return $this->draw($card);
    }

    /**
     * Returns all cards
     * @return \App\Card\Card[] 
     */
    public function getcards(): array
    {
        return $this->cards;
    }
}
