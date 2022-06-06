<?php

declare(strict_types=1);

namespace App\WiseBits\Services\Statistics\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO
{
    /**
     * @Assert\Length(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     * @Assert\NotBlank
     */
    protected string $cityCode;

    /**
     * @param string $cityCode
     */
    public function __construct(string $cityCode)
    {
        $this->cityCode = $cityCode;
    }

    /**
     * @return string
     */
    public function getCityCode(): string
    {
        return $this->cityCode;
    }
}
