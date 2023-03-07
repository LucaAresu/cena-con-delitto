<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Schema(
    title: 'ValidationErrorsResponse',
    properties: [
        new OA\Property(property: 'message', title: 'message', type: 'string', example: 'Some validation errors has happened'),
        new OA\Property(property: 'errors', title: 'errors', type: 'object', properties: [
            new OA\Property(property: 'key', title: 'key', type: 'string', example: 'the value is required'),
            new OA\Property(property: 'anotherKey', title: 'anotherKey', type: 'string', example: 'the value must be a number'),
        ]),
    ],
)]
readonly class ValidationErrorResponse
{
    private string $message;

    /** @var array<string, string> */
    private array $errors;

    /** @param array<string, string> $errors */
    private function __construct(string $message, array $errors = [])
    {
        $this->message = $message;
        $this->errors = $errors;
    }

    public static function createFromConstraintViolationList(string $message, ConstraintViolationListInterface $violations): self
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = (string) $violation->getMessage();
        }

        return new self($message, $errors);
    }

    public function message(): string
    {
        return $this->message;
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }
}
