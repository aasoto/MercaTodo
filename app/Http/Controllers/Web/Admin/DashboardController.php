<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Order\Services\ReportServices as OrderReportServices;
use App\Domain\Product\Services\ReportServices as ProductReportServices;
use App\Domain\User\Models\ModelHasRole as Role;
use App\Domain\User\Traits\AuthHasRole;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use AuthHasRole;

    public function index(
        OrderReportServices $order_reports,
        ProductReportServices $product_reports, ): Response
    {
        return Inertia::render('Dashboard', [
            'ordersByDay' => $order_reports->ordersByDay(10),
            'ordersByPaymentStatus' => $order_reports->ordersByPaymentStatus(),
            'productsByCategory' => $product_reports->productsByCategory(),
            'productsStatusByStock' => $product_reports->productsStatusByStock(),
            'productsByAvailability' => $product_reports->productsByAvailability(),
            'userRole' =>
                session('user_role') ? session('user_role') : $this->authHasRole(Role::getFromCache()),
        ]);
    }

}
