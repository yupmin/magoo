<?php

namespace Pachico\MagooTest;

use Pachico\Magoo\Magoo;
use Pachico\Magoo\MagooLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class MagooLoggerTest extends TestCase
{
    private MagooLogger $sut;
    private LoggerInterface $logger;
    private Magoo $magoo;

    public function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->magoo = new Magoo();
        $this->magoo->pushEmailMask();
        $this->sut = new MagooLogger($this->logger, $this->magoo);
    }

    public function dataProviderLogLevels(): array
    {
        return [
            [LogLevel::ALERT],
            [LogLevel::CRITICAL],
            [LogLevel::DEBUG],
            [LogLevel::EMERGENCY],
            [LogLevel::ERROR],
            [LogLevel::INFO],
            [LogLevel::NOTICE],
            [LogLevel::WARNING]
        ];
    }

    /**
     * @dataProvider dataProviderLogLevels
     */
    public function testLoggingLogLevelsCallMagooAndRedactsContent(string $logLevel): void
    {
        // Arrange
        $rawString = 'My email is foo@bar.com.';
        $maskedString = 'My email is ***@bar.com.';
        $this->logger->expects($this->once())->method($logLevel)->with($maskedString, [$maskedString]);
        // Act
        call_user_func_array([$this->sut, $logLevel], [$rawString, [$rawString]]);
    }

    public function testGetLoggerReturnsLogger(): void
    {
        // Act
        $output = $this->sut->getLogger();
        // Assert
        $this->assertSame($this->logger, $output);
    }

    /**
     * @dataProvider dataProviderLogLevels
     */
    public function testLogCallMagooAndRedactsContent($logLevel): void
    {
        // Arrange
        $rawString = 'My email is foo@bar.com.';
        $maskedString = 'My email is ***@bar.com.';
        $this->logger->expects($this->once())->method('log')->with($logLevel, $maskedString, [$maskedString]);
        // Act
        $this->sut->log($logLevel, $rawString, [$rawString]);
    }

    public function testGetMaskManagerReturnsMagoo(): void
    {
        // Act
        $output = $this->sut->getMaskManager();
        // Assert
        $this->assertSame($this->magoo, $output);
    }
}
