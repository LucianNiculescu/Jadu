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

    /**
     * @group integration
     * @covers ::anagram
     * @throws \JsonException
     */
    public function testAnagramIntegration()
    {

        // Valid anagram
        $response = $this->makeRequest('POST', '/validate/anagram', ['word' => 'coalface', 'comparison' => 'cacao elf']);

        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertTrue($body['success']);

        // Invalid anagram
        $response = $this->makeRequest('POST', '/validate/anagram', ['word' => 'coalface', 'comparison' => 'dark elf']);

        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);
    }

    /**
     * @group integration
     * @covers ::anagram
     * @throws \JsonException
     */
    public function testAnagramInvalidRequestIntegration()
    {
        // Invalid request method
        $response = $this->makeRequest('GET', '/validate/anagram', ['phrase' => 'The quick brown fox jumps over the lazy dog']);
        $this->assertEquals(405, $response->getStatusCode());

        // Missing required JSON keys
        $response = $this->makeRequest('POST', '/validate/anagram', ['catchphrase' => 'The quick brown fox jumps over the lazy dog']);
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);

        // Invalid request body
        $response = $this->makeRequest('POST', '/validate/anagram', '', false);
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);
    }

    /**
     * @group integration
     * @covers ::pangram
     * @throws \JsonException
     */
    public function testPangramIntegration()
    {

        // Valid pangram
        $response = $this->makeRequest('POST', '/validate/pangram', ['phrase' => 'The quick brown fox jumps over the lazy dog']);
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertTrue($body['success']);

        // Invalid pangram
        $response = $this->makeRequest('POST', '/validate/pangram', ['phrase' => 'The British Broadcasting Corporation (BBC) is a British public service broadcaster.']);
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);
    }

    /**
     * @group integration
     * @covers ::pangram
     * @throws \JsonException
     */
    public function testPangramInvalidRequestIntegration()
    {
        // Invalid request method
        $response = $this->makeRequest('GET', '/validate/pangram', ['phrase' => 'The quick brown fox jumps over the lazy dog']);
        $this->assertEquals(405, $response->getStatusCode());

        // Missing required JSON indexes
        $response = $this->makeRequest('POST', '/validate/pangram', ['catchphrase' => 'The quick brown fox jumps over the lazy dog']);
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);

        // Invalid request body
        $response = $this->makeRequest('POST', '/validate/pangram', '', false);
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['success']);
    }

    /**
     * @throws \JsonException
     */
    private function makeRequest(string $method, string $uri, $data, bool $parseJsonBody = true): Response
    {
        $this->client->request($method, $uri, [], [], [], $parseJsonBody ? json_encode($data, JSON_THROW_ON_ERROR) : $data);
        return $this->client->getResponse();
    }
}
