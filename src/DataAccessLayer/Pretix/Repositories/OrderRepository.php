<?php

namespace App\DataAccessLayer\Pretix\Repositories;

use App\DataAccessLayer\Pretix\Views\Order;
use App\DataAccessLayer\Pretix\Views\OrderPosition;

class OrderRepository extends PretixBaseRepository
{
    public function getOrderByCode(string $orderCode): Order
    {
        $uri = "orders/".$orderCode;
        return new Order($this->pretixApiClient->retrieve($uri));
    }

    public function getOrderPosition(int $id): OrderPosition
    {
        $uri = "orderpositions/".$id;
        return new OrderPosition($this->pretixApiClient->retrieve($uri));
    }

    public function downloadImage(string $url, string $path): string
    {
        realpath(dirname($path)) ?: mkdir(dirname($path), 0777, true);
        return $this->pretixApiClient->downloadImage($url, $path);
    }

    public function getOrders()
    {
        $orders   = $this->pretixApiClient->retrieveAll('orders', ['status' => 'p']);
        $orderObj = [];
        foreach ($orders as $order) {
            $orderObj[] = new Order($order);
        }
        return $orderObj;
    }
}
