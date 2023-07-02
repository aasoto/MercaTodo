<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Jobs\ProductExportJob;
use Illuminate\Http\Request;

class ProductExportController extends Controller
{
    public function __invoke(Request $request)
    {
        ProductExportJob::dispatch()->onQueue('export-import');

        return response()->json([
            'message' => 'Export file queued',
        ], 200);
    }
}
