<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\Event\Kernel\Exception;

use CenaConDelitto\Shared\Dto\ValidationErrorResponse;
use CenaConDelitto\Shared\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;

class RequestValidationExceptionListener
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function handleException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if (false === $throwable instanceof RequestValidationException) {
            return;
        }
        $validationErrorResponse = ValidationErrorResponse::createFromConstraintViolationList(
            'Some validation errors has happened',
            $throwable->violationList()
        );

        $response = new JsonResponse(
            $this->serializer->serialize($validationErrorResponse, 'json'), 400,
            json: true
        );

        $event->setResponse($response);
    }
}
