<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\Event\Kernel\Exception;

use CenaConDelitto\Shared\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RequestValidationExceptionListener
{
    public function handleException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof RequestValidationException) {
            return;
        }

        $response = new JsonResponse($this->mapErrors($exception->violationList()), 400);

        $event->setResponse($response);
    }

    /**
     * @return array<string, array<string, string>|string>
     */
    private function mapErrors(ConstraintViolationListInterface $violations): array
    {
        $data = [
            'message' => 'Some validation errors has happened',
            'errors' => [],
        ];

        foreach ($violations as $violation) {
            $data['errors'][$violation->getPropertyPath()] = (string) $violation->getMessage();
        }

        return $data;
    }
}
