<?php

namespace App\Util\Exceptions\Listener;

use App\Util\Exceptions\Exception\CustomExceptionInterface;
use App\Util\Exceptions\Exception\LogCustomExceptionInterface;
use App\Util\Exceptions\Exception\PublicException;
use App\Util\Exceptions\Response\PublicExceptionResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleExceptionEvent', 1],

                // Register this one with lower priority
                // So when the logging interface is used the logging method is called and after that the next method
                ['handleExceptionEventAfterLogging', -1]
            ]
        ];
    }

    public function handleExceptionEventAfterLogging(ExceptionEvent $event): void
    {
        // This method is called after the standard logging method
        // So we know this Exception is logged
        $this->handleException($event, true);
    }

    protected function handleException(ExceptionEvent $event, $isExceptionLogged = false): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof CustomExceptionInterface || !$exception->isPublicException()) {
            return;
        }

        // First pass: continue to allow the log method to pick this up
        if ($exception instanceof LogCustomExceptionInterface && !$isExceptionLogged) {
            return;
        }

        $statusCode = $exception->getHttpStatusCode();

        if ($exception instanceof PublicException) {
            $response = new JsonResponse(
                new PublicExceptionResponse(
                    $exception->getMessage(),
                    $exception->getData()
                )
            );
        } else {
            $response = new JsonResponse(
                $exception->getData()
            );
        }


        $response->setStatusCode($statusCode);
        $event->setResponse($response);
    }

    public function handleExceptionEvent(ExceptionEvent $event): void
    {
        $this->handleException($event);
    }
}

