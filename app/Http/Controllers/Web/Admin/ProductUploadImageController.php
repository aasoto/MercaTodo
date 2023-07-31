<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Dtos\ImageProductData;
use App\Domain\Product\Services\ImagesServices;
use App\Domain\Product\Traits\StorageFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\Product\ImageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductUploadImageController extends Controller
{
    use StorageFiles;

    public function create(): Response
    {
        return Inertia::render('Product/Image', []);
    }

    public function store(
        ImageRequest $request,
        ImagesServices $images_services): RedirectResponse
    {
        $file_name = $images_services->uploadSingleImage(
            ImageProductData::fromRequest($request)
        );

        return Redirect::route('products.index')->with('file_name', $file_name);
    }
}
