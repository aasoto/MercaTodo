<?php

namespace App\Domain\Product\Models;

use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property string code
 * @property string name
 * @method static UnitFactory factory(...$parameters)
 */
class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

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
