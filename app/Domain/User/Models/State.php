<?php

namespace App\Domain\User\Models;

use App\Domain\User\QueryBuilders\StateQueryBuilder;
use Database\Factories\StateFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * @method static StateFactory factory(...$parameters)
 */
class State extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * @param Builder $query
     * @return StateQueryBuilder
     */
    public function newEloquentBuilder($query): StateQueryBuilder
    {
        return new StateQueryBuilder($query);
    }

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'states', fn () => State::select('id', 'name')->orderBy('name')->get()
        );
    }

    protected static function newFactory(): Factory
    {
        return StateFactory::new();
    }
}
