<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Product\Dtos\ImportProductData;
use App\Http\Controllers\Controller;
use App\Http\Jobs\ProductImportJob;
use App\Http\Requests\Api\Admin\Product\ImportRequest;
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

        return response()->json([
            'message' => trans('Products queued', ['attribute' => 'file']),
            'file' => $data->products_file,
        ], 201);
    }
}
