<?php

namespace App\Console\Commands;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Services\Entities\Placetopay\Authentication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class consultSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consult-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consult the status of the payments who have not been correctly reported';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $orders = Order::query()
            ->where('payment_status', 'pending')
            ->get();

        $authentication = new Authentication();

        foreach ($orders as $order) {
            $result = Http::post(config('placetopay.url').config('placetopay.route.api').$order->request_id, [
                'auth' => $authentication->getAuth(),
            ]);

            if ($result->ok()) {
                $status = $result->json()['status']['status'];
                if ($status == 'APPROVED') {
                    $order->paid();
                    Log::channel('payment_webcheckout')
                        ->info('['.$result->status().'][AUTO][APPROVED] Payment reported successfully for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                            'response' => json_decode($result->body(), true),
                        ]);
                } elseif ($status == 'PENDING') {
                    $order->pending();
                    Log::channel('payment_webcheckout')
                        ->warning('['.$result->status().'][AUTO][PENDING] Payment is stil pending for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                            'response' => json_decode($result->body(), true),
                        ]);
                } elseif ($status == 'REJECTED') {
                    $order->canceled();
                    Log::channel('payment_webcheckout')
                        ->error('['.$result->status().'][AUTO][REJECTED] Payment has been rejected for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                            'response' => json_decode($result->body(), true),
                        ]);
                }
            }
        }
    }

}
