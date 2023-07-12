<?php

namespace App\Domain\Product\Models;

use App\Domain\Product\QueryBuilders\UnitQueryBuilder;
use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @method static Unit select(...$parameters)
 * @method static UnitFactory factory(...$parameters)
 */
class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    /**
     * @param Builder $query
     * @return UnitQueryBuilder
     */
    public function newEloquentBuilder($query): UnitQueryBuilder
    {
        return new UnitQueryBuilder($query);
    }

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'units', fn () => Unit::select('id', 'code', 'name')->get()
        );
    }

    protected static function newFactory(): Factory
    {
        return UnitFactory::new();
    }
}
