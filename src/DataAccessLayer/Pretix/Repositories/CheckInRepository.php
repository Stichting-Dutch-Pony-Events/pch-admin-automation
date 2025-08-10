<?php

namespace App\DataAccessLayer\Pretix\Repositories;

use App\DataAccessLayer\Pretix\Request\CheckInRequest;
use App\DataAccessLayer\Pretix\Views\CheckIn;
use App\DataAccessLayer\Pretix\Views\OrderPosition;
use App\DataAccessLayer\Pretix\Views\TicketSearch;

class CheckInRepository extends PretixBaseRepository
{
    public function checkIn(CheckInRequest $checkInRequest): CheckIn {
        $response = $this->pretixApiClient->post('checkinrpc/redeem/', $checkInRequest, false);
        return new CheckIn($response);
    }

    public function search(string $secret, int $checkInList): ?OrderPosition
    {
        $response = $this->pretixApiClient->retrieve('checkinrpc/search?list='.$checkInList.'&secret='.$secret, false);

        $ticketSearch = new TicketSearch($response);

        return count($ticketSearch->getResults()) === 1 ? $ticketSearch->getResults()[0] : null;
    }
}
