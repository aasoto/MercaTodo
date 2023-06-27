<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Traits\RandomColor;

class ReportServices
{
    use RandomColor;

    public function products_by_category(): array
    {
        $colors = array();
        $data = array();
        $labels = array();

        foreach (ProductCategory::getFromCache() as $key => $value) {
            array_push($data, count(Product::where('products_category_id', $value['id'])->get()));
            array_push($labels, ucwords($value['name']));
            array_push($colors, $this->generate_random_color());
        }

        return [
            'colors' => $colors,
            'data' => $data,
            'labels' => $labels,
        ];
    }

    public function products_status_by_stock(): array
    {
        $colors = ['rgb(25, 119, 3)', 'rgb(255, 204, 0)', 'rgb(223, 6, 52)'];
        $data = array();
        $labels = ['Abundantes: stock > 5', 'Escasos: stock <= 5', 'Agotados: stock = 0'];

        array_push($data, count(Product::where('stock', '>', 5)->get()));
        array_push($data, count(Product::where('stock', '<=', 5)->get()));
        array_push($data, count(Product::where('stock', 0)->get()));

        return [
            'colors' => $colors,
            'data' => $data,
            'labels' => $labels,
        ];
    }

    public function products_by_availability(): array
    {
        $colors = ['rgb(25, 119, 3)', 'rgb(223, 6, 52)'];
        $data = array();
        $labels = ['Habilitados', 'Inhabilitados'];

        array_push($data, count(Product::where('availability', '1')->get()));
        array_push($data, count(Product::where('availability', '0')->get()));

        return [
            'colors' => $colors,
            'data' => $data,
            'labels' => $labels,
        ];
    }
}
