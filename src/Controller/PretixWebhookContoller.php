<?php

namespace App\Controller;

use App\Application\Request\PretixWebhookRequest;
use App\Application\Service\ImportOrderApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PretixWebhookContoller extends AbstractController
{
    public function __construct(
        private readonly ImportOrderApplicationService $importOrderApplicationService
    ) {
    }

    #[Route('/pretix/webhook', name: 'pretix_webhook', methods: ['POST'])]
    public function onWebhook(
        #[MapRequestPayload] PretixWebhookRequest $pretixWebhookRequest
    ): Response {
        $this->importOrderApplicationService->importOrder(
            $pretixWebhookRequest->code,
            $pretixWebhookRequest->event,
        );

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
