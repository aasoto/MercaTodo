<?php

namespace App\Http\Jobs;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\User;
use App\Http\Mail\SendEmailImportProducts;
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

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const HEADERS = [
        'id' => 0,
        'name' => 1,
        'category' => 2,
        'barcode' => 3,
        'description' => 4,
        'price' => 5,
        'unit' => 6,
        'stock' => 7,
        'availability' => 8,
    ];

    public function __construct(
        private readonly string $path_file,
        private readonly User $user,
    )
    {}

    public function handle(): void
    {
        try {
            if (($file = fopen(Storage::path($this->path_file), 'r')) !== false) {
                fgetcsv($file);

                while (($row = fgetcsv($file)) !== false) {
                    $this->process_row($row);
                }

                fclose($file);

                Mail::to($this->user)->send(new SendEmailImportProducts(true));
            }
        } catch (\Exception $exception) {

            Mail::to($this->user)->send(new SendEmailImportProducts(false));

            Log::channel('import_products_file')
            ->error('Error importing products file', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ]);
        }
    }

    private function process_row(array $row): void
    {
        Product::query()->updateOrCreate([
            'id' => $row[self::HEADERS['id']],
        ], [
            'id' => $row[self::HEADERS['id']],
            'name' => $row[self::HEADERS['name']],
            'slug' => Str::slug($row[self::HEADERS['name']]),
            'products_category_id' => $this->get_product_category_id($row[self::HEADERS['category']]),
            'barcode' => $row[self::HEADERS['barcode']],
            'description' => $row[self::HEADERS['description']],
            'price' => $row[self::HEADERS['price']],
            'unit' => $this->get_unit_code($row[self::HEADERS['unit']]),
            'stock' => $row[self::HEADERS['stock']],
            'availability' => $this->get_availability($row[self::HEADERS['availability']]),
        ]);
    }

    private function get_product_category_id(string $name): int
    {
        $product_category = ProductCategory::query()->firstOrCreate([
            'name' => $name,
        ], [
            'name' => $name,
        ]);

        return $product_category->id;
    }

    private function get_unit_code(string $code): string
    {
        $unit = Unit::query()->firstOrCreate([
            'code' => $code,
        ], [
            'code' => $code,
            'name' => $code,
        ]);

        return $unit->code;
    }

    private function get_availability(string $status): bool
    {
        if ($status == 'enabled') {
            return true;
        } else {
            return false;
        }
    }
}
