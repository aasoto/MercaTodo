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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public mixed $user,
        public ?string $uuid = '',
    ) {}

    public function handle(): void
    {
        $headers = [
            'id',
            'name',
            'category',
            'barcode',
            'description',
            'price',
            'unit',
            'stock',
            'picture_1',
            'picture_2',
            'picture_3',
            'availability',
        ];

        if (!$this->uuid) {
            $this->uuid = Str::uuid()->serialize();
        }

        try {

            $file_name = sprintf('exports/%s.csv', $this->uuid);
            $this->createFile($file_name);
            $file = $this->openFile($file_name);
            fputcsv($file, $headers);

            Product::with('category')->with('product_unit')->chunk(10, function ($products) use ($file) {
                /**
                 * @var Product $product
                 */
                foreach ($products as $product) {
                    fputcsv($file, [
                        'id' => $product->id,
                        'name' => $product->name,
                        'category' => $product->category?->name,
                        'barcode' => $product->barcode,
                        'description' => $product->description,
                        'price' => $product->price,
                        'unit' => $product->product_unit?->name,
                        'stock' => $product->stock,
                        'picture_1' => $product->picture_1,
                        'picture_2' => $product->picture_2,
                        'picture_3' => $product->picture_3,
                        'availability' => $product->availability == '1' ? 'enabled' : 'disabled',
                    ]);
                }
            });

            fclose($file);

            Mail::to($this->user)->send(new SendEmailExportProducts($this->uuid ? $this->uuid : ''));

        } catch (\Exception $exception) {
            Log::channel('export_products_file')
            ->error('Error exporting products file', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ]);
        }

    }

    private function createFile(string $file_name): void
    {
        Storage::disk(config()->get('filesystem.default'))->put($file_name, '');
    }

    private function openFile(string $file_name): mixed
    {
        return fopen(Storage::disk(config()->get('filesystem.default'))->path($file_name), 'w');
    }
}
