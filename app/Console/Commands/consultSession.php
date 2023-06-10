<?php

namespace App\Console\Commands;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Services\Entities\Placetopay\Authentication;
use App\Domain\Order\Services\Entities\Placetopay\ReportStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        $orders = Order::where('payment_status', 'pending')
            ->orWhere('payment_status', 'waiting')
            ->orWhere('payment_status', 'verify_bank')
            ->get();

        $authentication = new Authentication();

        /**
         * @var Order $order
         */
        foreach ($orders as $order) {
            $response = Http::post(config('placetopay.url').config('placetopay.route.api').$order->request_id, [
                'auth' => $authentication->getAuth(),
            ]);

            $status = new ReportStatus($order, $response, 'AUTO');

            if ($response->ok()) {
                $status->saveOk();
            }
        }
    }

}
