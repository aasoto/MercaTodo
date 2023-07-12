<?php

namespace App\Domain\Product\Models;

use App\Domain\Product\QueryBuilders\ProductCategoryQueryBuilder;
use Database\Factories\ProductCategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $name
 * @method static ProductCategoryFactory factory(...$parameters)
 */
class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'products_categories';

    protected $fillable = ['name'];

    /**
     * @param Builder $query
     * @return ProductCategoryQueryBuilder
     */
    public function newEloquentBuilder($query): ProductCategoryQueryBuilder
    {
        return new ProductCategoryQueryBuilder($query);
    }

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'products_categories', fn () => ProductCategory::select('id', 'name')->orderBy('name')->get()
        );
    }

    protected static function newFactory(): Factory
    {
        return ProductCategoryFactory::new();
    }
}
