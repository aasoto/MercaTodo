<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Jobs\OrdersReportJob;
use App\Support\Exports\OrdersReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Report/Order/Index', [
            'success' => session('success'),
        ]);
    }

    public function export(Request $request): RedirectResponse
    {
        $path_file = 'reports/orders/orders_'.time().'.xlsx';
        (new OrdersReport)->queue($path_file)->chain([
            new OrdersReportJob($request->user(), $path_file),
        ]);

        return Redirect::route('report.index')->with('success', 'Orders report generated.');
    }
}
