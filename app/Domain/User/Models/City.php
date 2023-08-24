<?php

namespace App\Domain\User\Models;

use App\Domain\User\QueryBuilders\CityQueryBuilder;
use Database\Factories\CityFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * @property string $id
 * @property string $name
 * @property string $state_id
 * @method static City select(...$parameters)
 * @method static City orderBy(...$parameters)
 * @method static CityFactory factory(...$parameters)
 */
class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'state_id'];

    /**
     * @param Builder $query
     * @return CityQueryBuilder
     */
    public function newEloquentBuilder($query): CityQueryBuilder
    {
        return new CityQueryBuilder($query);
    }

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'cities', fn () => City::select('id', 'name', 'state_id')->orderBy('name')->get()
        );
    }

    protected static function newFactory(): Factory
    {
        return CityFactory::new();
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
