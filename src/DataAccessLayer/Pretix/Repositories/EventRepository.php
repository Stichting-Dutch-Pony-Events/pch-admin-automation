<?php

namespace App\DataAccessLayer\Pretix\Repositories;

use App\DataAccessLayer\Pretix\Views\Event;
use Symfony\Contracts\Cache\ItemInterface;

class EventRepository extends PretixBaseRepository
{
    public function getEventByCode(string $code): Event
    {
        return $this->cache->get('pretix-event-' . $code, function (ItemInterface $item) use ($code): Event {
            $item->expiresAfter(3600); // Cache for 1 hour

            $event = $this->pretixApiClient->retrieveRaw('events/' . $code, false);

            return $this->serializer->deserialize($event->getContent(), Event::class, 'json');
        });
    }
}
