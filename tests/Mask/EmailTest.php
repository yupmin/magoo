<?php

declare(strict_types=1);

namespace Pachico\MagooTest\Mask;

use Pachico\Magoo\Mask\Email;
use Pachico\MagooTest\TestCase;

class EmailTest extends TestCase
{
    private Email $sut;

    protected function setUp(): void
    {
        $this->sut = new Email(['localReplacement' => '*', 'domainReplacement' => '*']);
    }

    public function dataProviderInputExpectedOutput(): array
    {
        return [
            [
                'To donate write to ted@crilly.com, please',
                'To donate write to ***@**********, please'
            ],
            [
                'In case of dragons, report to dougal@mcguire.net',
                'In case of dragons, report to ******@***********',
            ],
            [
                'Booz expert: jack@hackett.org',
                'Booz expert: ****@***********',
            ],
            [
                'To get more sandwitches, mail me at mrs@doyle.co.uk',
                'To get more sandwitches, mail me at ***@***********',
            ],
            [
                'To report any of the above, contact me at bishop@brennan.biz',
                'To report any of the above, contact me at ******@***********',
            ],
            [
                'This is just a random string',
                'This is just a random string',
            ],
            [
                'It almost looks like an email: asdas@',
                'It almost looks like an email: asdas@',
            ],
            [
                'This is almost interesting: foo.bar.com',
                'This is almost interesting: foo.bar.com',
            ],
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

    public function testReplacementsAreUsedForRedacting(): void
    {
        $this->sut = new Email(['localReplacement' => '*']);
        $this->assertSame($this->sut->mask('bernard@black.com'), '*******@black.com');

        $this->sut = new Email(['domainReplacement' => '*']);
        $this->assertSame($this->sut->mask('manny@bianco.co.uk'), 'manny@************');

        $this->sut = new Email();
        $this->assertSame($this->sut->mask('fran@katzenjammer.net'), '****@katzenjammer.net');
    }
}
