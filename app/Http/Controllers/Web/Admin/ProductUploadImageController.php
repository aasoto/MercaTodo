<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Dtos\ImageProductData;
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
        return Inertia::render('Product/Image', [
            'fileName' => session('file_name'),
        ]);
    }

    public function store(ImageRequest $request): RedirectResponse
    {
        $data = ImageProductData::fromRequest($request);

        $file_name = $this->upload($data->image_file, 'images/products', rand(0, 100));

        return Redirect::route('product.image.create')->with('file_name', $file_name);
    }
}
