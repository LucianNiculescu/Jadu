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
    public function testIsPalindrome()
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
    public function testIsPalindromeWithNonAlphabeticalData()
    {
        $result = $this->instance->isPalindrome('a14n£$%3n1a');
        $this->assertTrue($result);
    }

}
