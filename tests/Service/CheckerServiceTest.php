<?php

namespace App\Tests\Service;

use App\Service\CheckerService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\CheckerService
 */
final class CheckerServiceTest extends TestCase
{
    private CheckerService $instance;

    protected function setUp(): void
    {
        $this->instance = new CheckerService();
    }

    /**
     * @group unit
     * @covers ::isPalindrome
     */
    public function testIsPalindrome(): void
    {
        $result = $this->instance->isPalindrome('anna');
        $this->assertTrue($result);

        $result = $this->instance->isPalindrome('none');
        $this->assertFalse($result);
    }

    /**
     * @group unit
     * @covers ::isPalindrome
     */
    public function testIsPalindromeWithNonAlphabeticalData(): void
    {
        $result = $this->instance->isPalindrome('a14n£$%3n1a');
        $this->assertTrue($result);
    }

    /**
     * @group unit
     * @covers ::isAnagram
     */
    public function testIsAnagram(): void
    {
        $result = $this->instance->isAnagram('coalface', 'cacao elf');
        $this->assertTrue($result);

        $result = $this->instance->isAnagram('coalface', 'dark elf');
        $this->assertFalse($result);
    }

    /**
     * @group unit
     * @covers ::isAnagram
     */
    public function testIsAnagramNonAlphabeticalData(): void
    {
        $result = $this->instance->isAnagram('coa1$lf a c e', 'c1 aca!o elf');
        $this->assertTrue($result);
    }

}
