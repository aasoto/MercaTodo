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
        'picture_1' => 8,
        'picture_2' => 9,
        'picture_3' => 10,
        'availability' => 11,
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
                    $this->processRow($row);
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

    /**
     * @param array<mixed> $row
     */
    private function processRow(array $row): void
    {
        Product::query()->updateOrCreate([
            'id' => $row[self::HEADERS['id']],
        ], [
            'id' => $row[self::HEADERS['id']],
            'name' => $row[self::HEADERS['name']],
            'slug' => Str::slug($row[self::HEADERS['name']]),
            'products_category_id' => $this->getProductCategoryId($row[self::HEADERS['category']]),
            'barcode' => $row[self::HEADERS['barcode']],
            'description' => $row[self::HEADERS['description']],
            'price' => $row[self::HEADERS['price']],
            'unit' => $this->getUnitCode($row[self::HEADERS['unit']]),
            'stock' => $row[self::HEADERS['stock']],
            'picture_1' => $row[self::HEADERS['picture_1']],
            'picture_2' => $row[self::HEADERS['picture_2']],
            'picture_3' => $row[self::HEADERS['picture_3']],
            'availability' => $this->getAvailability($row[self::HEADERS['availability']]),
        ]);
    }

    private function getProductCategoryId(string $name): int
    {
        /**
         * @var ProductCategory $product_category
         */
        $product_category = ProductCategory::query()->firstOrCreate([
            'name' => $name,
        ], [
            'name' => $name,
        ]);

        return $product_category->id;
    }

    private function getUnitCode(string $name): string
    {
        /**
         * @var Unit $unit
         */
        $unit = Unit::query()->firstOrCreate([
            'name' => $name,
        ], [
            'code' => strtolower($name),
            'name' => $name,
        ]);

        return $unit->code;
    }

    private function getAvailability(string $status): bool
    {
        if ($status == 'enabled') {
            return true;
        } else {
            return false;
        }
    }
}
