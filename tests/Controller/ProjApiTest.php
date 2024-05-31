<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ProjApiTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * Test the route for the api page.
     */
    public function testIndex(): void
    {
        $this->client->request('GET', '/proj/api');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Api routes');
    }

    /**
     * Test the route for the game page.
     */
    public function testGame(): void
    {
        $this->client->request('GET', '/proj/api/game');

        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson(strval($response->getContent()));
    }

    /**
     * Test the route for the place card page before starting game
     */
    public function testPlaceCardBeforeGame(): void
    {
        $this->client->request('POST', '/proj/api/placecard/1/1');
        $this->assertResponseStatusCodeSame(405);
    }

    /**
     * Test the route for the place card page before starting game
     */
    public function testPointsBeforeGame(): void
    {
        $this->client->request('POST', '/proj/api/points');
        $this->assertResponseStatusCodeSame(405);
    }

    /**
     * Test the route for the game page.
     */
    public function testPlayGame(): void
    {
        // Start game
        $this->client->request('GET', '/proj/api/game');
        $this->assertResponseIsSuccessful();

        // Place again after starting game
        $this->client->request('POST', '/proj/api/placecard/1/1');
        $this->assertResponseStatusCodeSame(200);

        // Place again on same place
        $this->client->request('POST', '/proj/api/placecard/1/1');
        $this->assertResponseStatusCodeSame(406);

        // Get points
        $this->client->request('GET', '/proj/api/points');
        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson(strval($response->getContent()));

        // Reset game
        $this->client->request('GET', '/proj/api/game?reset');
        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson(strval($response->getContent()));
    }

    /**
     * Test High Score
     */
    public function testHighScore(): void
    {
        $this->client->request('GET', '/proj/api/high-score');
        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson(strval($response->getContent()));
    }
}
