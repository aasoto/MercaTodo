<?php

namespace Tests\Feature\Traits;

use App\Models\Product;

/**
 * Remove images generated during the execution of tests
 */
trait refreshStorage
{
    public function cleanProductsImages(): void
    {
        $products = Product::select('picture_1', 'picture_2', 'picture_3')->get();

        foreach ($products as $key => $value) {
            unlink(public_path('images/products/'.$value['picture_1']));
            unlink(public_path('images/products/'.$value['picture_2']));
            unlink(public_path('images/products/'.$value['picture_3']));
        }
    }

    public function cleanProductImage(): void
    {
        $product = Product::select('picture_1')->orderBy('id', 'DESC')->first();
        unlink(public_path('images/products/'.$product['picture_1']));
    }
}
