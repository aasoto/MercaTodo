<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Product\Dtos\ImportProductData;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Jobs\ProductImportJob;
use App\Http\Requests\Api\Admin\Product\ImportRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductImportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ImportRequest $request): JsonResponse
    {
        $data = ImportProductData::fromRequest($request);

        /**
         * @var User $auth_user
         */
        $auth_user = $request->user();

        ProductImportJob::dispatch($data->products_file, $auth_user);

        return response()->json([
            'message' => trans('Products queued', ['attribute' => 'file']),
            'file' => $data->products_file,
        ], 201);
    }
}
