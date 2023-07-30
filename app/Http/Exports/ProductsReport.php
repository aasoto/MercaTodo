<?php

namespace App\Http\Exports;

use App\Domain\Product\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductsReport implements FromQuery, WithColumnFormatting, WithHeadings
{
    use Exportable;

    /**
     * @param array<mixed> $filters
     */
    public function __construct(
        private array $filters,
    )
    {}

    public function query()
    {
        /**
         * @var Builder|EloquentBuilder|Relation $query;
         */
        $query = Product::query()
            -> whereSearch(isset($this->filters['search']) ? $this->filters['search'] : null)
            -> whereCategory(isset($this->filters['category']) ? $this->filters['category'] : null)
            -> whereUnit(isset($this->filters['unit_code']) ? $this->filters['unit_code'] : null)
            -> whenAvailability(isset($this->filters['availability']) ? $this->filters['availability'] : null)
            -> whereSoldOut(isset($this->filters['sold_out']) ? $this->filters['sold_out'] : null)
            -> whereStockBetween(
                isset($this->filters['min_stock']) ? $this->filters['min_stock'] : null,
                isset($this->filters['max_stock']) ? $this->filters['max_stock'] : null,
            )
            -> wherePriceBetween(
                isset($this->filters['min_price']) ? $this->filters['min_price'] : null,
                isset($this->filters['max_price']) ? $this->filters['max_price'] : null,
            )
            -> select(
                'products.id',
                'products.name',
                'products_categories.name as category',
                'products.price',
                'units.name as unit',
                'products.stock',
                'products.availability',
            )
            -> join('products_categories', 'products.products_category_id', 'products_categories.id')
            -> join('units', 'products.unit', 'units.code')
            -> orderBy('products.id');

        return $query;
    }

    /**
     * @return array<mixed>
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Categoría del producto',
            'Precio',
            'Unidad',
            'Stock',
            'Disponibilidad',
        ];
    }

    /**
     * @return array<mixed>
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_USD,
            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function prepareRows(Collection $rows): Collection
    {
        return $rows->transform(function ($product) {

            $product->name = ucwords($product->name);
            $product->category = ucwords($product->category);

            if ($product->availability == 1) {
                $product->availability = 'Habilitado';
            } else {
                $product->availability = 'Inhabilitado';
            }

            return $product;
        });
    }
}
