<?php

namespace App\DataAccessLayer\Pretix\Repositories;

use App\DataAccessLayer\Pretix\Views\Invoice;

class InvoicesRepository extends PretixBaseRepository
{
    /**
     * @param  string|null  $order
     * @return Invoice[]
     */
    public function getInvoices(?string $order = null): array
    {
        $parameters = [];
        if ($order !== null) {
            $parameters['order'] = $order;
        }
        $invoices   = $this->pretixApiClient->retrieveAll("invoices", $parameters);
        $invObjects = [];
        foreach ($invoices as $inv) {
            $invObjects[] = new Invoice($inv);
        }
        return $invObjects;
    }

    public function getInvoiceByNumber(string $invoiceNumber): Invoice
    {
        $uri = "invoices/".$invoiceNumber;
        return new Invoice($this->pretixApiClient->retrieve($uri));
    }
}
