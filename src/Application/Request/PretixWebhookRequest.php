<?php

namespace App\Application\Request;

class PretixWebhookRequest
{
    public function __construct(
        public int    $notification_id,
        public string $organizer,
        public string $event,
        public string $code,
        public string $action
    ) {
    }
}
