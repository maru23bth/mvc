<?php

namespace App\Card;

use App\Card\Card;

/**
 * @property-read \App\Card\Card[] $cards
 */
class CardCollection
{
    /**
     * 
     * @var \App\Card\Card[]
     */
    protected array $cards;

    /**
     * Shuffle cards
     * @return void
     */
    public function shuffle()
    {
        shuffle($this->cards);
    }

    /**
     * Draw one random card from collection
     * @return Card|false
     */
    public function draw(Card $card = null): Card|false
    {
        if (empty($this->cards)) {
            return false;
        }

        if ($card) {
            $key = array_search($card, $this->cards);
            if ($key === false) {
                return false;
            }
            return array_splice($this->cards, intval($key), 1)[0];
        }

        return array_splice($this->cards, intval(array_rand($this->cards)), 1)[0];
    }

    /**
     * Sort cards
     * @return void
     */
    public function sort()
    {
        usort($this->cards, function (Card $cardA, Card $cardB) {
            if ($cardA->color !== $cardB->color) {
                return $cardA->color <=> $cardB->color;
            } elseif ($cardA->suit !== $cardB->suit) {
                return $cardA->suit <=> $cardB->suit;
            } elseif ($cardA->number !== $cardB->number) {
                return array_search($cardA->number, Card::NUMBERS) <=> array_search($cardB->number, Card::NUMBERS);
            }
            return 0;
        });
    }

    /**
     * Check if $card exists in collection of cards
     * @param Card $card
     * @return bool
     */
    public function exists(Card $card): bool
    {
        return array_search($card, $this->cards) !== false;
    }

    public function __get(string $name): mixed
    {
        switch ($name) {
            case 'cards':
                echo "__get(cards)";
                return $this->cards;
        }

        trigger_error(
            'Undefined property via __get(): ' . $name,
            E_USER_NOTICE
        );
        return null;
    }

    public function __toString(): string
    {
        return 'Collection of ' . count($this->cards) . ' cards';
    }
}
