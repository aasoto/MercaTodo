<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'state_id'];

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'cities', fn () => City::select('id', 'name', 'state_id')->get()
        );
    }
}
