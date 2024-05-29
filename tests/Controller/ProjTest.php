<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ProjTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * Test the route for the index page.
     */
    public function testIndex(): void
    {
        $this->client->request('GET', '/proj/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Proj Index');
    }

    /**
     * Test the route for the about page.
     */
    public function testAbout(): void
    {
        $this->client->request('GET', '/proj/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Om projekt');
    }

    /**
     * Test the route for the game page.
     */
    public function testGame(): void
    {
        $this->client->request('GET', '/proj/game');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Poker Square');
    }

    /**
     * Test the route for the place card page before starting game
     */
    public function testPlaceCardBeforeGame(): void
    {
        $this->client->request('GET', '/proj/placecard/1/1');
        $this->assertResponseRedirects('/proj/game');
    }

    /**
     * Test the route for the game page.
     */
    public function testPlayGame(): void
    {
        // Start game
        $this->client->request('GET', '/proj/game');
        $this->assertResponseIsSuccessful();

        // Place again after starting game
        $this->client->request('GET', '/proj/placecard/1/1');
        $this->assertResponseRedirects('/proj/game');

        // Place again on same place
        $this->client->request('GET', '/proj/placecard/1/1');
        $this->assertResponseRedirects('/proj/game');

        // Reset game
        $this->client->request('GET', '/proj/game?reset');
        $this->assertResponseIsSuccessful();
    }
}
