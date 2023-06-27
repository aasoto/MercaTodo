<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Services\ReportServices;
use App\Domain\User\Models\ModelHasRole as Role;
use App\Domain\User\Traits\AuthHasRole;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use AuthHasRole;

    public function index(ReportServices $reports): Response
    {
        return Inertia::render('Dashboard', [
            'productsByCategory' => $reports->products_by_category(),
            'productsStatusByStock' => $reports->products_status_by_stock(),
            'productsByAvailability' => $reports->products_by_availability(),
            'userRole' =>
                session('user_role') ? session('user_role') : $this->authHasRole(Role::getFromCache()),
        ]);
    }

}
