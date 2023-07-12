<?php

namespace App\Domain\User\Models;

use Database\Factories\TypeDocumentFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @method static TypeDocumentFactory factory(...$parameters)
 */
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

    protected static function newFactory(): Factory
    {
        return TypeDocumentFactory::new();
    }
}
