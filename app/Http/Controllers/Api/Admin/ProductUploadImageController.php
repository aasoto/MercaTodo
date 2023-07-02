<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Product\Dtos\ImageProductData;
use App\Domain\Product\Traits\StorageFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Product\ImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductUploadImageController extends Controller
{
    use StorageFiles;

    public function __invoke(ImageRequest $request): JsonResponse
    {
        $data = ImageProductData::fromRequest($request);

        $file_name = $this->upload($data->image_file, 'images/products', rand(0, 100));

        return response()->json([
            'message' => trans('Product image saved.', ['attribute' => 'file_name']),
            'file_name' => $file_name,
        ], 201);
    }
}
