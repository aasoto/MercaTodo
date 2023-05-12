<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
}
