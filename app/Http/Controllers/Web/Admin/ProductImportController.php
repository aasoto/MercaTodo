<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Dtos\ImportProductData;
use App\Domain\User\Models\User;
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

        /**
         * @var User $auth_user
         */
        $auth_user = $request->user();

        ProductImportJob::dispatch($data->products_file, $auth_user);

        return Redirect::route('products.index')->with('success', 'Products imported.');
    }
}
