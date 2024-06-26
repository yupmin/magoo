<?php

declare(strict_types=1);

namespace Pachico\MagooTest\Mask;

use Pachico\Magoo\Mask\Creditcard;
use Pachico\MagooTest\TestCase;

/**
 * All credit card numbers have been randomly generated using http://www.getcreditcardnumbers.com/
 */
class CreditCardTest extends TestCase
{
    private Creditcard $sut;

    protected function setUp(): void
    {
        $this->sut = new Creditcard();
    }

    public static function dataProviderInputExpectedOutput(): array
    {
        return [
            [
                'My credit card is 4556168690125914. Please, spread it!',
                'My credit card is ************5914. Please, spread it!',
            ],
            [
                'My credit cards are 6011 3885 3731 4927 and 5465-7136-4763-2236. Buy something!',
                'My credit cards are ************4927 and ************2236. Buy something!',
            ],
            [
                'Creditcard rampage! 6011 612890653518, 601167 7315389477, 60117 4607031 4770, 6011-3885373-14927',
                'Creditcard rampage! ************3518, ************9477, ************4770, ************4927',
            ],
            [
                'This string is not sentive',
                'This string is not sentive',
            ],
            [
                'My credit card is 4556168690125814',
                'My credit card is 4556168690125814',
            ],
            [
                'All my credit cards are fake: 5544239360013512, 4916184779428320',
                'All my credit cards are fake: 5544239360013512, 4916184779428320',
            ]
        ];
    }

    /**
     * @dataProvider dataProviderInputExpectedOutput
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
