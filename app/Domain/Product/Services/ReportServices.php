<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Traits\RandomColor;

class ReportServices
{
    use RandomColor;

    /**
     * @return array<mixed>
     */
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

    /**
     * @return array<mixed>
     */
    public function products_status_by_stock(): array
    {
        $products = Product::get();

        $colors = ['rgb(25, 119, 3)', 'rgb(255, 204, 0)', 'rgb(223, 6, 52)'];
        $data = array();
        $labels = ['Abundantes: stock > 5', 'Escasos: stock <= 5', 'Agotados: stock = 0'];

        $abundant = 0;
        $scarce = 0;
        $sold_out = 0;

        foreach ($products as $key => $value) {
            if ($value['stock'] > 5) {
                $abundant++;
            }
            if ($value['stock'] <= 5) {
                $scarce++;
            }
            if ($value['stock'] == 0) {
                $sold_out++;
            }
        }

        array_push($data, $abundant);
        array_push($data, $scarce);
        array_push($data, $sold_out);

        return [
            'colors' => $colors,
            'data' => $data,
            'labels' => $labels,
        ];
    }

    /**
     * @return array<mixed>
     */
    public function products_by_availability(): array
    {
        $products = Product::get();

        $colors = ['rgb(25, 119, 3)', 'rgb(223, 6, 52)'];
        $data = array();
        $labels = ['Habilitados', 'Inhabilitados'];

        $enabled = 0;
        $disabled = 0;

        foreach ($products as $key => $value) {
            if ($value['availability'] == '1') {
                $enabled++;
            } else {
                $disabled++;
            }
        }

        array_push($data, $enabled);
        array_push($data, $disabled);

        return [
            'colors' => $colors,
            'data' => $data,
            'labels' => $labels,
        ];
    }
}
