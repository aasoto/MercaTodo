<?php

namespace App\Http\Jobs;

use App\Domain\Product\Models\Product;
use App\Http\Mail\SendEmailExportProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $uuid = '',
    ) {}

    public function handle(): void
    {
        $headers = [
            'name',
            'category',
            'barcode',
            'description',
            'price',
            'unit',
            'stock',
            'availability',
        ];

        if (!$this->uuid) {
            $this->uuid = Str::uuid()->serialize();
        }
        $file_name = sprintf('exports/%s.csv', $this->uuid);
        $this->create_file($file_name);
        $file = $this->open_file($file_name);
        fputcsv($file, $headers);

        Product::with('category')->with('product_unit')->chunk(10, function ($products) use ($file) {
            /**
             * @var Product $product
             */
            foreach ($products as $product) {
                fputcsv($file, [
                    'name' => $product->name,
                    'category' => $product->category->name,
                    'barcode' => $product->barcode,
                    'description' => $product->description,
                    'price' => $product->price,
                    'unit' => $product->product_unit->name,
                    'stock' => $product->stock,
                    'availability' => $product->availability == '1' ? 'enabled' : 'disabled',
                ]);
            }
        });

        fclose($file);

        Mail::to('admin@example.com')
            ->cc('notification@mercatodo.com')
            ->send(new SendEmailExportProducts($this->uuid));
    }

    private function create_file(string $file_name): void
    {
        Storage::disk(config()->get('filesystem.default'))->put($file_name, '');
    }

    private function open_file(string $file_name)
    {
        return fopen(Storage::disk(config()->get('filesystem.default'))->path($file_name), 'w');
    }
}
