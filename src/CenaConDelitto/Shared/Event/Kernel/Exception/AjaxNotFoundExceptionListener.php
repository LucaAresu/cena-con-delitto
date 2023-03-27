<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\Event\Kernel\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AjaxNotFoundExceptionListener
{

    public function handleException(ExceptionEvent $event): void
    {
        if (false === $event->getThrowable() instanceof NotFoundHttpException) {
            return;
        }

        if (false === $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $response = new JsonResponse([
            'error' => 'Entity not found'
        ]);

        $event->setResponse($response);
    }
}
