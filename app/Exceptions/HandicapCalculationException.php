<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class HandicapCalculationException extends Exception
{
    private string $userMessage;
    private string $logMessage;
    private array $context = [];

    /**
     * Create a new HandicapCalculationException
     *
     * @param string $userMessage Message to display to the user
     * @param string $logMessage Message to log internally (for debugging)
     * @param array $context Additional context data for logging
     * @param int $code Exception code
     */
    public function __construct(
        string $userMessage,
        string $logMessage = '',
        array $context = [],
        int $code = 0
    ) {
        $this->userMessage = $userMessage;
        $this->logMessage = $logMessage ?: $userMessage;
        $this->context = $context;

        parent::__construct($this->userMessage, $code);
    }

    /**
     * Get the user-friendly message
     */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    /**
     * Get the internal log message with full context
     */
    public function getLogMessage(): string
    {
        return $this->logMessage;
    }

    /**
     * Get the context data for logging
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Log the exception with appropriate level and context
     */
    public function log(string $level = 'error'): void
    {
        $context = array_merge($this->context, [
            'file' => $this->file,
            'line' => $this->line,
            'trace' => $this->getTraceAsString(),
        ]);

        Log::log($level, $this->logMessage, $context);
    }

    /**
     * Render the exception for API responses
     */
    public function render()
    {
        $this->log();

        return response()->json([
            'success' => false,
            'message' => $this->userMessage,
            'error' => class_basename($this),
        ], 422);
    }
}
