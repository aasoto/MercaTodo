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

    public function orders_by_payment_status(): array
    {
        $color_bars = [
            'rgba(22, 163, 74, 0.2)', //green
            'rgba(250, 204, 21, 0.2)', //yellow
            'rgba(220, 38, 38, 0.2)', //red
            'rgba(217, 119, 6, 0.2)', //amber - approval_partial
            'rgba(147, 51, 234, 0.2)', //purple - waiting
            'rgba(234, 88, 12, 0.2)', //orange - verify_bank
            'rgba(82, 82, 91, 0.2)', //zinc - partial_expired
        ];

        $color_border_bars = [
            'rgb(22, 163, 74)', //green
            'rgb(250, 204, 21)', //yellow
            'rgb(220, 38, 38)', //red
            'rgb(217, 119, 6)', //amber - approval_partial
            'rgb(147, 51, 234)', //purple - waiting
            'rgb(234, 88, 12)', //orange - verify_bank
            'rgb(82, 82, 91)', //zinc - partial_expired
        ];

        $data = array();
        $labels = config('paymentStatus');

        $orders = Order::get();
        $counter = 0;

        foreach ($labels as $label) {
            foreach ($orders as $order) {
                if ($order['payment_status'] == $label) {
                    $counter++;
                }
            }
            array_push($data, $counter);
            $counter = 0;
        }

        return [
            'colorBars' => $color_bars,
            'colorBorderBars' => $color_border_bars,
            'data' => $data,
            'labels' => $labels,
        ];
    }
}
