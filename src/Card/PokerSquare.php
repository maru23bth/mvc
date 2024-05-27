<?php

namespace App\Card;

use App\Card\Card;
use App\Card\DeckOfCards;

/**
 * Class Game21 for playing game 21
 * @package App\Card
 */
class PokerSquare
{
    private DeckOfCards $deck;

    /**
     * @var array<int, array<int, Card|null>>
     */
    public array $cardGrid;
    public ?Card $activeCard = null;

    /**
     * Constructor to initiate the game
     * @return void
     */
    public function __construct()
    {
        // Setup
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();

        // Grid 5x5 to place cards in.
        $this->cardGrid = array_fill(0, 5, array_fill(0, 5, null));

        // Draw first card
        $this->draw();
    }

    /**
     * Draw a card from the deck
     * @return Card|false
     */
    private function draw()
    {
        // If one card is active return it
        if ($this->activeCard) {
            return $this->activeCard;
        }

        // If game ended
        if ($this->gameEnded()) {
            return false;
        }

        // Draw new card
        $card = $this->deck->draw();
        if (!$card) {
            return false;
        }

        // Set active card
        $this->activeCard = $card;
        return $card;
    }

    /**
     * Place the active card on the grid
     * @param int $row
     * @param int $col
     * @return bool
     */
    public function place(int $row, int $col): bool
    {
        // Check if card is active
        if (!$this->activeCard) {
            return false;
        }

        // Check if the position is empty
        if ($this->cardGrid[$row][$col]) {
            return false;
        }

        // Place the card
        $this->cardGrid[$row][$col] = $this->activeCard;

        $this->activeCard = null;
        $this->draw(); // Draw next card
        return true;
    }

    /**
     * Check if the game has ended
     * @return bool
     */
    public function gameEnded(): bool
    {
        foreach ($this->cardGrid as $row) {
            if (in_array(null, $row)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the total points for the game
     * @return int
     */
    public function getTotalPoints(): int
    {
        $points = $this->getPoints();
        $total = 0;
        foreach ($points as $type) {
            $total += array_sum($type);
        }
        return $total;
    }

    /**
     * Get points for the rows and columns
     * @return array<string, array<int>>
     */
    public function getPoints(): array
    {
        $points = ['row' => [], 'col' => []];

        // Calculate points for rows
        foreach ($this->cardGrid as $row => $cards) {
            if (in_array(null, $cards)) {
                $points['row'][$row] = 0;
                continue;
            }
            /** @var array<int, Card> $cards */
            $points['row'][$row] = self::handValue($cards);
        }

        // Calculate points for columns
        for ($col = 0; $col < 5; $col++) {
            $cards = array_map(fn ($row) => $this->cardGrid[$row][$col], range(0, 4));
            if (in_array(null, $cards)) {
                $points['col'][$col] = 0;
                continue;
            }

            /** @var array<int, Card> $cards */
            $points['col'][$col] = self::handValue($cards);
        }
        return $points;
    }

    /**
     * Get points for the array of $cards
     * @param array<int, Card> $cards
     * @return int
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private static function handValue(array $cards): int
    {
        $faces = array_map(fn ($card) => $card->getValue(), $cards);
        sort($faces, SORT_NUMERIC);

        $suits = array_map(fn ($card) => $card->suit, $cards);
        sort($suits);

        // Identify a flush
        $flush = count(array_unique($suits)) === 1;

        // Identify a straight
        $straight = $faces[4] - $faces[0] === 4 && count(array_unique($faces)) === 5;

        // Identify duplicates
        $duplicates = array_count_values($faces);
        $duplicates = array_filter($duplicates, fn ($value) => $value > 1);

        if ($flush && $straight && $faces[4] === 14) {
            return 100; // Royal flush
        }
        if ($flush && $straight) {
            return 75; // Straight flush
        }
        if (!empty($duplicates) && max($duplicates) === 4) {
            return 50; // Four of a kind
        }
        if (count($duplicates) === 2 && max($duplicates) === 3) {
            return 25; // Full house
        }
        if ($flush) {
            return 20; // Flush
        }
        if ($straight) {
            return 15; // Straight
        }
        if (!empty($duplicates) && max($duplicates) === 3) {
            return 10; // Three of a kind
        }
        if (count($duplicates) === 2) {
            return 5; // Two pair
        }
        if (!empty($duplicates) && max($duplicates) === 2) {
            return 2; // One pair
        }

        return 0; // Default
    }



}
