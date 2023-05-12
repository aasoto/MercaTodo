<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TypeDocument extends Model
{
    use HasFactory;

    protected $table = 'type_documents';

    protected $fillable = ['code', 'name'];

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'type_documents', fn () => TypeDocument::select('id', 'code', 'name')->get()
        );
    }
}
