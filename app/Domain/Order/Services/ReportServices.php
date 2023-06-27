<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Models\Order;

class ReportServices
{
    public function orders_by_day(int $interval = 8): array
    {
        $color = 'rgb(75, 192, 192)';
        $data = array();
        $labels = array();

        $orders = Order::get();
        $current_date = date('Y-m-d');
        $orders_by_day = 0;

        for ($i = $interval; $i >= 0; $i--) {
            /**
             * @var Order $order
             */
            foreach ($orders as $order) {
                $order_date = strtotime($order['purchase_date']);
                $searching_date = date('Y-m-d', strtotime($current_date.'- '.$i.' days'));
                if (date('Y-m-d', $order_date) == $searching_date) {
                    $orders_by_day++;
                }
            }
            array_push($labels, $searching_date);
            array_push($data, $orders_by_day);
            $orders_by_day = 0;
        }

        return [
            'color' => $color,
            'data' => $data,
            'labels' => $labels,
        ];
    }
}
