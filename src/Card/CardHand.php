<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardCollection;

/**
 * Class CardHand for a collection of cards in a hand
 * @package App\Card
 */
class CardHand extends CardCollection
{
    /**
     * Add a card to the hand
     * @param Card $card
     * @return void
     */
    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Remove a card from the hand if exists
     * @param Card $card
     * @return Card|false
     */
    public function remove(Card $card): Card|false
    {
        return $this->draw($card);
    }

    /**
     * Returns all cards
     * @return Card[]
     */
    public function getcards(): array
    {
        return $this->cards;
    }
}
