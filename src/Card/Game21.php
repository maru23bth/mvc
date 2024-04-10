<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

define('BANK', 0);
define('PLAYER', 1);

class Game21
{

    private DeckOfCards $deck;
    public CardHand $bank;
    public CardHand $player;
    private static $BANK_STOPS_AT = 17;

    public function __construct()
    {
        // Setup
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->bank = new CardHand();
        $this->player = new CardHand();

        // Draw first card
        $this->drawCard($this->player);
    }

    /**
     * Plays bank untill $BANK_STOPS_AT
     * @return void 
     */
    private function playBank(): void {
        while ($this->getHandValues($this->bank)[0] < self::$BANK_STOPS_AT) {
            $this->drawCard($this->bank);
        }
    }

    /**
     * Draw one more card for player
     * @return bool false if we can't get more cards.
     */
    public function playerDraw(): bool {
        $return = $this->drawCard($this->player);


        return $return;
    }

    public function playerStop(): void {
        $this->playBank();
    }

    /**
     * False if game not ended.
     * 0 if bank winns, 1 if player winns
     * @return bool|int 
     */
    public function winner(): bool|int {
        // If game not started
        if (!count($this->player->cards)) {
            return false;
        }
        // If player is over 21
        if ($this->over21($this->player)) {
            return BANK; // Bank winns
        }
        // Bank has not drawn any cards.
        if (!count($this->bank->cards)) {
            return false;
        }
        // If bank is over 21
        if ($this->over21($this->bank)) {
            return PLAYER; // Player winns
        }
        // If player has higher score than bank
        if ($this->getBestHandValue($this->player) > $this->getBestHandValue($this->bank)) {
            return PLAYER;
        }
        return BANK;
    }

    /**
     * Draw a new card for $hand
     * @param CardHand $hand 
     * @return bool False if over 21 or deck is empty
     */
    private function drawCard(CardHand &$hand): bool {
        if ($this->over21($hand)) {
            return false;
        }
        $card = $this->deck->draw();
        if (!$card) {
            return false;
        }
        $hand->add($card);
        return true;
    }

    public function getBestHandValue(CardHand &$hand): int {
        $values = $this->getHandValues($hand); // Get all possible values
        return array_reduce($values, fn($best, $value) => ($value <= 21 && $value > $best)? $value:$best, 0);
    }

    /**
     * 
     * @param CardHand $hand 
     * @return int[] Array of possible values of hand sorted asc.
     */
    public function getHandValues(CardHand &$hand): array {
        $return = [0];
        foreach ($hand->cards as $card) {
            $cardValue = $card->getValue();

            $newReturn = [];
            foreach ($return as $sum) { // Add $value to each possible value
                $newReturn[] = $sum + $cardValue;
                if ($cardValue == 14) { // If Ace value can also be 1
                    $newReturn[] = $sum + 1;
                }
            }
            $return = $newReturn;
        }

        // Remove dublicates and sort
        $return = array_unique($return);
        sort($return);
        return $return;
    }

    /**
     * 
     * @param CardHand $hand 
     * @return bool True if hand is over 21.
     */
    public function over21(CardHand &$hand): bool {
        $handValues = $this->getHandValues($hand);
        return boolval($handValues[0] > 21);
    }

    public function __toString(): string
    {
        return 'Game 21 deck has ' . count($this->deck->cards) . ' cards left, player has ' . $this->getHandValues($this->player)[0];
    }
}
