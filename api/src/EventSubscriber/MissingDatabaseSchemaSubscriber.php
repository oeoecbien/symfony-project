<?php

namespace App\EventSubscriber;

use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class MissingDatabaseSchemaSubscriber implements EventSubscriberInterface
{
    private const MESSAGE = 'Tables absentes : exécuter « php bin/console doctrine:migrations:migrate » (ou « php bin/console app:database:refresh -n » pour schéma + données de dev).';

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 512],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->isMissingAppTable($event->getThrowable())) {
            return;
        }

        $request = $event->getRequest();
        $event->setResponse($this->createResponse($request));
    }

    private function isMissingAppTable(Throwable $throwable): bool
    {
        for ($e = $throwable; $e !== null; $e = $e->getPrevious()) {
            if ($e instanceof TableNotFoundException && $this->messageTargetsAppTable($e->getMessage())) {
                return true;
            }
            if ($this->isMysqlTableNotFoundForAppTables($e->getMessage())) {
                return true;
            }
        }

        return false;
    }

    private function messageTargetsAppTable(string $message): bool
    {
        return str_contains($message, '`character`')
            || str_contains($message, '.character')
            || str_contains($message, '`building`')
            || str_contains($message, '.building');
    }

    private function isMysqlTableNotFoundForAppTables(string $message): bool
    {
        return (bool) preg_match(
            "/Table '[^']+\\.(character|building)' doesn't exist/i",
            $message
        );
    }

    private function createResponse(Request $request): Response
    {
        if ($this->wantsJson($request)) {
            return new JsonResponse(
                [
                    'error' => 'database_schema_missing',
                    'message' => self::MESSAGE,
                ],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return new Response(
            self::MESSAGE."\n",
            Response::HTTP_SERVICE_UNAVAILABLE,
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    }

    private function wantsJson(Request $request): bool
    {
        if (str_starts_with($request->getPathInfo(), '/characters') || str_starts_with($request->getPathInfo(), '/buildings')) {
            return true;
        }
        if ($request->getPreferredFormat() === 'json') {
            return true;
        }
        $accept = (string) $request->headers->get('Accept', '');
        if (str_contains($accept, 'application/json')) {
            return true;
        }

        return false;
    }
}
