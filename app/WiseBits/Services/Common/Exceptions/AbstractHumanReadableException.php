<?php

declare(strict_types=1);

namespace App\WiseBits\Services\Common\Exceptions;

class AbstractHumanReadableException extends \Exception implements HumanReadableInterface
{
    protected string $userMessage;

    /**
     * @param string $errorMessage
     * @param string $userMessage
     * @param \Throwable|null $previous
     * @param int|null $code
     */
    public function __construct(string $errorMessage, string $userMessage, \Throwable $previous = null, int $code = null)
    {
        parent::__construct($errorMessage, $code ?: 0, $previous);
        $this->userMessage = $userMessage;
    }

    /**
     * @inheritDoc
     */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
