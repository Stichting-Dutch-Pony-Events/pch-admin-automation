<?php

namespace App\Application\Request;

class PretixWebhookRequest
{
    public function __construct(
        public int    $notificationId,
        public string $organizer,
        public string $event,
        public string $code,
        public string $action
    ) {
    }
}
