<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Dtos\ImportProductData;
use App\Http\Controllers\Controller;
use App\Http\Jobs\ProductImportJob;
use App\Http\Requests\Web\Admin\Product\ImportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductImportController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Product/Import', []);
    }

    public function store(ImportRequest $request): RedirectResponse
    {
        $data = ImportProductData::fromRequest($request);

        ProductImportJob::dispatch(
            $data->products_file,
            $request->user(),
        )->onQueue('export-import');

        return Redirect::route('products.index')->with('success', 'Products imported.');
    }
}
