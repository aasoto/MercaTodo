<?php

namespace App\Models\Spatie;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class ModelHasRole extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'model_id'];

    public $timestamps = false;

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'roles', fn () => Role::select('id', 'name')->get()
        );
    }
}
