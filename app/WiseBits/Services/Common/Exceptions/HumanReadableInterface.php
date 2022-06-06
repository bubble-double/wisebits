<?php

declare(strict_types=1);

namespace App\WiseBits\Services\Common\Exceptions;

interface HumanReadableInterface
{
    /**
     * A description of the error that can be safely shown to the user
     *
     * @return string
     */
    public function getUserMessage(): string;
}
