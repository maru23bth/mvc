<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game21.
 */
class Game21Test extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateGame21(): void
    {
        // Create Game21 object
        $game21 = new Game21();
        $this->assertInstanceOf("\App\Card\Game21", $game21);

        // Check player hand contains one card
        $this->assertCount(1, $game21->player->cards);

        // Check winner is false when game is not ended
        $this->assertFalse($game21->winner());

        // Check bank hand contains one card
        $this->assertCount(0, $game21->bank->cards);

        // Players stops
        $game21->playerStop();

        // Check winner is set
        $this->assertIsInt($game21->winner());

        // Check __toString
        $res = (string) $game21;
        $this->assertStringContainsString("player has", $res);
    }


    /**
     * Construct object and play over 21
     */
    public function testPlayOver21(): void
    {
        // Create Game21 object
        $game21 = new Game21();

        // Draw cards until player has 21
        while ($game21->playerDraw()) {
            $game21->winner();
        }

        $this->assertEquals(BANK, $game21->winner());

    }

}
