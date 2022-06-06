<?php

declare(strict_types=1);

namespace App\WiseBits\Services\Common\Exceptions;

use App\WiseBits\Helpers\ObjectHelper;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidatorException extends \Exception
{
    public function __construct(ConstraintViolationListInterface $violations, ?\Throwable $previous = null)
    {
        $errorMessage = $this->getMessagesFromViolations($violations);

        parent::__construct($errorMessage, 0, $previous);
    }

    /**
     * @param ConstraintViolationListInterface $violations
     *
     * @return string
     */
    protected function getMessagesFromViolations(ConstraintViolationListInterface $violations): string
    {
        $errorMessage = 'Validation errors.';
        /** @var ConstraintViolation $violation */
        foreach ($violations as $i => $violation) {
            $paramName = $violation->getPropertyPath();
            $invalidValue = $violation->getInvalidValue();
            $message = $violation->getMessage();
            $validationSubject = $violation->getRoot();

            $subjectName = $validationSubject && is_object($validationSubject)
                ? ObjectHelper::getClassNameWithoutNamespace(get_class($validationSubject))
                : $validationSubject;

            $errorMessage .= sprintf(
                ' %s) Param: "%s.%s". Invalid value: %s. Error message: "%s".',
                $i + 1,
                $subjectName,
                $paramName,
                $invalidValue,
                $message
            );
        }

        return $errorMessage;
    }
}
