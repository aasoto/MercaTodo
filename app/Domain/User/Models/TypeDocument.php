<?php

namespace App\Domain\User\Models;

use App\Domain\User\QueryBuilders\TypeDocumentQueryBuilder;
use Database\Factories\TypeDocumentFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
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

    /**
     * @param Builder $query
     * @return TypeDocumentQueryBuilder
     */
    public function newEloquentBuilder($query): TypeDocumentQueryBuilder
    {
        return new TypeDocumentQueryBuilder($query);
    }

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
