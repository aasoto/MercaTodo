<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\StoreRequest;
use App\Http\Requests\Dashboard\Product\UpdateRequest;
use App\Models\Product;
use App\Models\Unit;
use App\Traits\useCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    use useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        return Inertia::render('Product/Index', [
            'products' => Product::query()
                -> select(
                        'products.name',
                        'products.slug',
                        'products_categories.name as category',
                        'products.price',
                        'units.name as unit',
                        'products.stock',
                        'products.availability',
                    )
                -> join('products_categories', 'products.products_category_id', 'products_categories.id')
                -> join('units', 'products.unit', 'units.code')
                -> orderBy('products.id')
                -> paginate(10),
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products_categories = $this->getProductsCategories();
        $units = $this->getUnits();
        return Inertia::render('Product/Create', [
            'products_categories' => $products_categories,
            'units' => $units,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $counter = 0;
        if (isset($data['picture_1'])) {
            $counter++;
            $data['picture_1'] = $filename = time().$counter.'.'.$data['picture_1']->extension();
            $request->picture_1->move(public_path('images/products'), $filename);
            // $request->picture_1->storeAs('images/products', $filename, 'public');
        }

        if (isset($data['picture_2'])) {
            $counter++;
            $data['picture_2'] = $filename = time().$counter.'.'.$data['picture_2']->extension();
            $request->picture_2->move(public_path('images/products'), $filename);
            // $request->picture_2->storeAs('images/products', $filename, 'public');
        }

        if (isset($data['picture_3'])) {
            $counter++;
            $data['picture_3'] = $filename = time().$counter.'.'.$data['picture_3']->extension();
            $request->picture_3->move(public_path('images/products'), $filename);
            // $request->picture_3->storeAs('images/products', $filename, 'public');
        }

        Product::create($data);

        return Redirect::route('products.index')->with('success', 'Product created.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        return Inertia::render('Product/Show', [
            'product' => Product::select(
                    'products.name',
                    'products.slug',
                    'products_categories.name as category',
                    'products.description',
                    'products.price',
                    'units.name as unit',
                    'products.stock',
                    'products.picture_1',
                    'products.picture_2',
                    'products.picture_3'
                )
                -> join('products_categories', 'products.products_category_id', 'products_categories.id')
                -> join('units', 'products.unit', 'units.code')
                -> where('slug', $slug)
                -> first(),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        return Inertia::render('Product/Edit', [
            'product' => Product::select(
                    'products.id',
                    'products.name',
                    'products.products_category_id',
                    'products.barcode',
                    'products.description',
                    'products.price',
                    'products.unit',
                    'products.stock',
                    'products.picture_1',
                    'products.picture_2',
                    'products.picture_3',
                    'products.availability'
                )
                -> where('slug', $slug)
                -> first(),
            'products_categories' => $this->getProductsCategories(),
            'units' => $this->getUnits(),
            'success' => session('success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id, string $files)
    {
        $data = $request->validated();
        $files = json_decode($files);
        $counter = 0;

        if (isset($data['picture_1'])) {
            $counter++;
            $data['picture_1'] = $filename = time().$counter.'.'.$data['picture_1']->extension();
            $request->picture_1->move(public_path('images/products'), $filename);

            unlink('images/products/'.$files->picture_1);
        } else {
            unset($data['picture_1']);
        }

        if (isset($data['picture_2'])) {
            $counter++;
            $data['picture_2'] = $filename = time().$counter.'.'.$data['picture_2']->extension();
            $request->picture_2->move(public_path('images/products'), $filename);

            if (isset($files->picture_2)) {
                unlink('images/products/'.$files->picture_2);
            }
        } else {
            unset($data['picture_2']);
        }

        if (isset($data['picture_3'])) {
            $counter++;
            $data['picture_3'] = $filename = time().$counter.'.'.$data['picture_3']->extension();
            $request->picture_3->move(public_path('images/products'), $filename);

            if (isset($files->picture_3)) {
                unlink('images/products/'.$files->picture_3);
            }
        } else {
            unset($data['picture_3']);
        }

        Product::where('id', $id)->update($data);

        return Redirect::route('product.edit', $data['slug'])->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $product = Product::where('slug', $slug)->get();

        if ($product[0]->picture_1) {
            unlink('images/products/'.$product[0]->picture_1);
        }

        if ($product[0]->picture_2) {
            unlink('images/products/'.$product[0]->picture_2);
        }

        if ($product[0]->picture_3) {
            unlink('images/products/'.$product[0]->picture_3);
        }

        Product::where('slug', $slug)->delete();

        return Redirect::route('products.index')->with('success', 'Product deleted.');
    }
}
