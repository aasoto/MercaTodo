<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Report;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\Product\QueryBuilders\ProductQueryBuilder;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Http\Exports\ProductsReport;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ReportProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private ProductCategory $category;
    private Unit $unit;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        $this->category = ProductCategory::factory()->create();
        $this->unit = Unit::factory()->create();

        $this->user = User::factory()->create()->assignRole('admin');
        $this->product = Product::factory()->create([
            'stock' => 5,
        ]);
    }

    public function test_can_search_product_applaying_all_filters(): void
    {
        $response = $this->actingAs($this->user)
            ->get('product_report?search='.$this->product->name.'&category='.$this->category->name.'&minStock=4&maxStock=7&minPrice='.($this->product->price - 100).'&maxPrice='.($this->product->price + 100).'&unitCode='.$this->unit->code.'&availability=true&soldOut=false');

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Product/Create')
                -> has('filters')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> where('name', $this->product->name)
                    -> where('category', $this->category->name)
                    -> where('price', $this->product->price)
                    -> where('unit', $this->unit->name)
                    -> where('stock', $this->product->stock)
                    -> etc()
                )
                -> has('productsCategories')
                -> has('units')
                -> has('success')
        );
    }

    public function test_can_list_disabled_products(): void
    {
        $product_disabled = Product::factory()->create([
            'availability' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->get('product_report?availability=false');

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Product/Create')
                -> has('filters')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> where('name', $product_disabled->name)
                    -> where('category', $this->category->name)
                    -> where('price', $product_disabled->price)
                    -> where('unit', $this->unit->name)
                    -> where('stock', $product_disabled->stock)
                    -> etc()
                )
                -> has('productsCategories')
                -> has('units')
                -> has('success')
        );
    }

    public function test_can_search_product_by_min_price(): void
    {
        $response = $this->actingAs($this->user)
            ->get('product_report?minPrice='.$this->product->price);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Product/Create')
                -> has('filters')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> where('name', $this->product->name)
                    -> where('category', $this->category->name)
                    -> where('price', $this->product->price)
                    -> where('unit', $this->unit->name)
                    -> where('stock', $this->product->stock)
                    -> etc()
                )
                -> has('productsCategories')
                -> has('units')
                -> has('success')
        );
    }

    public function test_can_search_product_by_max_price(): void
    {
        $response = $this->actingAs($this->user)
            ->get('product_report?maxPrice='.$this->product->price);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Product/Create')
                -> has('filters')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> where('name', $this->product->name)
                    -> where('category', $this->category->name)
                    -> where('price', $this->product->price)
                    -> where('unit', $this->unit->name)
                    -> where('stock', $this->product->stock)
                    -> etc()
                )
                -> has('productsCategories')
                -> has('units')
                -> has('success')
        );
    }

    public function test_can_search_product_by_min_stock(): void
    {
        $response = $this->actingAs($this->user)
            ->get('product_report?minStock='.$this->product->stock);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Product/Create')
                -> has('filters')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> where('name', $this->product->name)
                    -> where('category', $this->category->name)
                    -> where('price', $this->product->price)
                    -> where('unit', $this->unit->name)
                    -> where('stock', $this->product->stock)
                    -> etc()
                )
                -> has('productsCategories')
                -> has('units')
                -> has('success')
        );
    }

    public function test_can_search_product_by_max_stock(): void
    {
        $response = $this->actingAs($this->user)
            ->get('product_report?maxStock='.$this->product->stock);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Product/Create')
                -> has('filters')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> where('name', $this->product->name)
                    -> where('category', $this->category->name)
                    -> where('price', $this->product->price)
                    -> where('unit', $this->unit->name)
                    -> where('stock', $this->product->stock)
                    -> etc()
                )
                -> has('productsCategories')
                -> has('units')
                -> has('success')
        );
    }

    public function test_can_search_sold_out_products(): void
    {
        $product_sold_out = Product::factory()->create([
            'stock' => 0,
        ]);

        $response = $this->actingAs($this->user)
            ->get('product_report?soldOut=true');

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Product/Create')
                -> has('filters')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> where('name', $product_sold_out->name)
                    -> where('category', $this->category->name)
                    -> where('price', $product_sold_out->price)
                    -> where('unit', $this->unit->name)
                    -> where('stock', $product_sold_out->stock)
                    -> etc()
                )
                -> has('productsCategories')
                -> has('units')
                -> has('success')
        );
    }

    public function test_can_queue_report_of_products(): void
    {
        Excel::fake();

        $time = strval(time());

        $response = $this->actingAs($this->user)
            ->post(route('product.report.export'), [
                'search' => $this->product->name,
                'time' => $time,
            ]);

        Excel::assertQueued('reports/products/products_'.$time.'.xlsx');

        $response->assertRedirect(route('product.report.create'))
        ->assertSessionHasAll(['success' => 'Products report generated.']);
    }

    public function test_can_return_query_with_the_expected_product_from_the_export_class(): void
    {
        $products_report = new ProductsReport([
            'min_price' => $this->product->price,
        ]);

        $response = $products_report->query();

        $this->assertInstanceOf(ProductQueryBuilder::class, $response);
    }
}
