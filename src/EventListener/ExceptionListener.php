<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ExceptionListener
{
    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exeption = $event->getThrowable();
        $message = sprintf(
            'Error: %s with code: %s',
            $exeption->getMessage(),
            $exeption->getCode()
        );

        $response = new JsonResponse();

        $response->setData([
            'status' => 'error',
            'message' => $message,
        ]);

        $event->setResponse($response);	
    }
}
