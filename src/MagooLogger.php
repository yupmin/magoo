<?php

declare(strict_types=1);

namespace Pachico\Magoo;

use Psr\Log\LoggerInterface;

/**
 * MagooLogger acts as a middleware between your application and a PSR3 logger
 * masking every message passed to it
 */
class MagooLogger implements LoggerInterface
{
    private LoggerInterface $logger;

    private MaskManagerInterface $maskManager;

    private MagooArray $magooArray;

    public function __construct(LoggerInterface $logger, MaskManagerInterface $maskManager)
    {
        $this->logger = $logger;
        $this->maskManager = $maskManager;
        $this->magooArray = new MagooArray($maskManager);
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getMaskManager(): MaskManagerInterface
    {
        return $this->maskManager;
    }

    /**
     * @param string $message
     */
    public function emergency($message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'emergency'], $maskedArguments);
    }

    /**
     * @param string $message
     */
    public function alert($message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'alert'], $maskedArguments);
    }

    /**
     * @param string $message
     */
    public function critical($message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'critical'], $maskedArguments);
    }

    /**
     * @param string $message
     */
    public function error($message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'error'], $maskedArguments);
    }

    /**
     * @param string $message
     */
    public function warning($message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'warning'], $maskedArguments);
    }

    /**
     * @param string $message
     */
    public function notice($message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'notice'], $maskedArguments);
    }

    /**
     * @param string $message
     */
    public function info($message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'info'], $maskedArguments);
    }

    /**
     * @param string $message
     */
    public function debug($message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        call_user_func_array([$this->logger, 'debug'], $maskedArguments);
    }

    /**
     * @param string $level
     * @param string $message
     */
    public function log($level, $message, array $context = [])
    {
        $maskedArguments = $this->maskLogArguments($message, $context);
        array_unshift($maskedArguments, $level);
        call_user_func_array([$this->logger, 'log'], $maskedArguments);
    }

    /**
     * @param string $message
     *
     * @return array Masked arguments
     */
    private function maskLogArguments($message, array $context)
    {
        return $this->magooArray->getMasked([$message, $context]);
    }
}
