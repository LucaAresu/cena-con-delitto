<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RequestValidationException extends BadRequestHttpException
{
    private ConstraintViolationListInterface $violationList;

    public static function create(ConstraintViolationListInterface $errori): self
    {
        $instance = new self('Errori di validazione della request');

        $instance->violationList = $errori;

        return $instance;
    }

    public function violationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
