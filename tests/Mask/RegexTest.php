<?php

declare(strict_types=1);

namespace Pachico\MagooTest\Mask;

use Pachico\Magoo\Mask\Regex;
use Pachico\MagooTest\TestCase;

class RegexTest extends TestCase
{
    protected Regex $sut;

    protected function setUp(): void
    {
        $this->sut = new Regex(['replacement' => '*', 'regex' => '(\d+)']);
    }

    public static function dataProviderIntputExpectedOutput(): array
    {
        return [
            [
                'This string has 12345 digits, which is more than 6789',
                'This string has ***** digits, which is more than ****'
            ],
            [
                '1-2-3-4-5-6-7-8-9-0',
                '*-*-*-*-*-*-*-*-*-*',
            ],
            [
                'I have no digits',
                'I have no digits',
            ]
        ];
    }

    /**
     * @dataProvider dataProviderIntputExpectedOutput
     */
    public function testMaskRedactsCorrectly(string $input, string $expectedOutput): void
    {
        // Arrange
        // Act
        $output = $this->sut->mask($input);
        // Assert
        $this->assertSame($expectedOutput, $output);
    }
}
