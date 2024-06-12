<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \App\Controller\CheckerController
 */
final class CheckerControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::createClient();
        $this->client = self::getClient();
    }

    /**
     * @group integration
     * @covers ::palindrome
     * @throws \JsonException
     */
    public function testPalindromeIntegration()
    {
        // Valid palindrome
        $response = $this->makeRequest('POST', '/validate/palindrome', ['word' => 'anna']);
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertTrue($body['success']);

        // Invalid palindrome
        $response = $this->makeRequest('POST', '/validate/palindrome', ['word' => 'none']);
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);
    }

    /**
     * @group integration
     * @covers ::palindrome
     * @throws \JsonException
     */
    public function testPalindromeInvalidRequestIntegration()
    {
        // Invalid request method
        $response = $this->makeRequest('GET', '/validate/palindrome', ['word' => 'anna']);
        $this->assertEquals(405, $response->getStatusCode());

        // Missing required JSON keys
        $response = $this->makeRequest('POST', '/validate/palindrome', ['someword' => 'anna']);
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);

        // Invalid request body
        $response = $this->makeRequest('POST', '/validate/palindrome', '', false);
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);
    }

    private function makeRequest(string $method, string $uri, $data, bool $parseJsonBody = true): Response
    {
        $this->client->request($method, $uri, [], [], [], $parseJsonBody ? json_encode($data) : $data);
        return $this->client->getResponse();
    }
}
