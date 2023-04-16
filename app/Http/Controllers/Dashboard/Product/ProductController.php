<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Unit;
use App\Traits\useCache;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    use useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $products = Product::query()
            -> select(
                    'products.name',
                    'products.slug',
                    'products_categories.name as category',
                    'products.price',
                    'products.unit',
                    'products.stock',
                    'products.availability',
                )
            -> join('products_categories', 'products.products_category_id', 'products_categories.id')
            -> orderBy('products.id')
            -> paginate(10);

        $units = $this->getUnits();

        return Inertia::render('Product/Index', [
            'products' => $products,
            'units' => $units,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
