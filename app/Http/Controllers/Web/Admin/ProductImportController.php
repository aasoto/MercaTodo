<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Dtos\ImportProductData;
use App\Http\Controllers\Controller;
use App\Http\Jobs\ProductImportJob;
use App\Http\Requests\Web\Admin\Product\ImportRequest;
use Illuminate\Http\Request;

class ProductImportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ImportRequest $request)
    {
        $data = ImportProductData::fromRequest($request);

        ProductImportJob::dispatch(
            $data->products_file,
            $request->user(),
        )->onQueue('export-import');
    }
}
