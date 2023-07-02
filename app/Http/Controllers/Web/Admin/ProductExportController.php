<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Jobs\ProductExportJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductExportController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        ProductExportJob::dispatch($request->user())->onQueue('export-import');

        return Redirect::route('products.index')->with('success', 'Products exported.');
    }
}
