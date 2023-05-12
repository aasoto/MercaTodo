<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
}
