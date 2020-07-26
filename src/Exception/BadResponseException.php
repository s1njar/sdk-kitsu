<?php

namespace S1njar\Kitsu\Exception;

use Exception;
use Throwable;

/**
 * Class BadResponseException
 */
class BadResponseException extends Exception
{
    /**
     * BadResponseException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}