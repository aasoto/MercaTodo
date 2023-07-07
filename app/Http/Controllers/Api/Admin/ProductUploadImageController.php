<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Product\Dtos\ImageProductData;
use App\Domain\Product\Services\ImagesServices;
use App\Domain\Product\Traits\StorageFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Product\ImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductUploadImageController extends Controller
{
    use StorageFiles;

    public function __invoke(
        ImageRequest $request,
        ImagesServices $images_services): JsonResponse
    {
        $file_name = $images_services->upload_single_image(
            ImageProductData::fromRequest($request)
        );

        return response()->json([
            'message' => trans('Product image saved.', ['attribute' => 'file_name']),
            'file_name' => $file_name,
        ], 201);
    }
}
