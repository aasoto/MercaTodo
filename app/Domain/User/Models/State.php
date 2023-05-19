<?php

namespace App\Domain\User\Models;

use Database\Factories\StateFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @method static StateFactory factory(...$parameters)
 */
class State extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'states', fn () => State::select('id', 'name')->get()
        );
    }

    protected static function newFactory(): Factory
    {
        return StateFactory::new();
    }
}
