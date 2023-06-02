<?php

namespace App\Console\Commands;

use App\Domain\Order\Models\Order;
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
            ->where('payment_status', '=', 'pending')
            ->get();

        foreach ($orders as $order) {
            $result = Http::post(config('placetopay.url').'/api/session/'.$order->request_id, [
                'auth' => $this->getAuth()
            ]);

            if ($result->ok()) {
                $status = $result->json()['status']['status'];
                if ($status == 'APPROVED') {
                    $order->paid();
                    Log::channel('payment_webcheckout')
                        ->info('[AUTO][APPROVED] Payment reported successfully for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                            'response' => json_decode($result->body(), true),
                        ]);
                } elseif ($status == 'PENDING') {
                    $order->pending();
                    Log::channel('payment_webcheckout')
                        ->warning('[AUTO][PENDING] Payment is stil pending for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                            'response' => json_decode($result->body(), true),
                        ]);
                } elseif ($status == 'REJECTED') {
                    $order->canceled();
                    Log::channel('payment_webcheckout')
                        ->error('[AUTO][REJECTED] Payment has been rejected for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                            'response' => json_decode($result->body(), true),
                        ]);
                }
            }
        }
    }

    private function getAuth(): array
    {
        $nonce = Str::random();
        $seed = date('c');

        return [
            'login' => config('placetopay.login'),
            'tranKey' => base64_encode(
                hash(
                    'sha256',
                    $nonce.$seed.config('placetopay.tranKey'),
                    true
                )
            ),
            'nonce' => base64_encode($nonce),
            'seed' => $seed,
        ];
    }
}
