<?php

namespace Pachico\MagooTest;

use Pachico\Magoo\Magoo;

class MagooTest extends TestCase
{
    protected Magoo $sut;

    protected function setUp(): void
    {
        $this->sut = new Magoo();
    }

    public function testChainedMasksShouldWorkAsExpected(): void
    {
        // Arrange
        $customMask = new Mask\CustomMask(['replacement' => 'bar']);

        // Act
        $this->sut->pushCreditCardMask('*')
            ->pushMask($customMask)
            ->pushEmailMask('*', '_')
            ->pushByRegexMask('/(email)+/m', '*');

        // Assert
        $this->assertSame(
            $this->sut->getMasked('My foo email is roy@trenneman.com and my credit card is 6011792594656742'),
            'My bar ***** is ***@_____________ and my credit card is ************6742'
        );
    }

    public function testPushingMasksReturnMagooInstance(): void
    {
        // Arrange
        // Act
        // Assert
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->pushCreditCardMask());
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->pushEmailMask());
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->pushMask(new Mask\CustomMask([])));
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->pushByRegexMask('/foo/'));
        $this->assertInstanceOf('Pachico\Magoo\Magoo', $this->sut->reset());
    }

    public function testResetCleansAnyPreviouslySetMask(): void
    {
        // Arrange
        $this->sut->pushCreditCardMask('*')->pushEmailMask('*', '_')->reset();
        $string = 'My foo email is roy@trenneman.com and my credit card is 6011792594656742';
        // Act
        $output = $this->sut->getMasked($string);
        // Assert
        $this->assertSame($string, $output);
    }

    public function testEmailMaskRedactsEmailsCorrectly(): void
    {
        // Arrange
        $this->sut->pushEmailMask('*');
        // Act
        $output = $this->sut->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742');
        // Assert
        $this->assertSame('My email is ***@trenneman.com and my credit card is 6011792594656742', $output);
    }

    public function testCreditcardMaskRedactsCCCorrectly(): void
    {
        // Arrange
        $this->sut->pushCreditCardMask('*');
        // Act
        $output = $this->sut->getMasked('My email is roy@trenneman.com and my credit card is 6011792594656742');
        // Assert
        $this->assertSame('My email is roy@trenneman.com and my credit card is ************6742', $output);
    }

    public function dataProviderRegexMaskRedactsStringsCorrectly(): array
    {
        return [
            [
                '/[a-zA-Z]+/m',
                'This is 1 string',
                '**** ** 1 ******',
            ],
            [
                '',
                'This 1 string that will not be masked since there is no valid regex',
                'This 1 string that will not be masked since there is no valid regex',
            ]
        ];
    }

    /**
     * @dataProvider dataProviderRegexMaskRedactsStringsCorrectly
     */
    public function testRegexMaskRedactsStringsCorrectly(string $regex, string $input, string $expectedOutput): void
    {
        // Arrange
        $this->sut->reset()->pushByRegexMask($regex, '*');
        // Act
        $output = $this->sut->getMasked($input);
        // Arrange
        $this->assertSame($expectedOutput, $output);
    }

    public function testNoMaskReturnsUnalteredInput(): void
    {
        // Arrange
        $string = 'My email is roy@trenneman.com and my credit card is 6011792594656742';
        // Act
        $output = $this->sut->getMasked($string);
        // Assert
        $this->assertSame($string, $output);
    }

    public function testCustomMaskAreCalledIfPassed(): void
    {
        // Arrange
        $customMask = new Mask\CustomMask(['replacement' => 'bar']);
        $this->sut->pushMask($customMask);
        // Act
        $output = $this->sut->getMasked('Some foo foo foo');
        // Assert
        $this->assertSame('Some bar bar bar', $output);
    }

    /**
     * Test that only strings can be passed to getMasked
     */
    public function testGetMaskedThrowsExceptionIfWrongInput(): void
    {
        $this->expectException(\TypeError::class);
        // Arrange

        // Act
        $this->sut->getMasked(['Not a string']);

        // Assert
    }
}
