<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Jobs\ProductExportJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductExportController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        ProductExportJob::dispatch($request->user());

        return response()->json([
            'message' => 'Export file queued',
        ], 200);
    }
}
