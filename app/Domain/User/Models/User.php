<?php

namespace App\Domain\User\Models;

use App\Domain\User\QueryBuilders\UserQueryBuilder;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $id
 * @property string $type_document
 * @property string $number_document
 * @property string $first_name
 * @property string $second_name
 * @property string $surname
 * @property string $second_surname
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $birthdate
 * @property string $gender
 * @property string $phone
 * @property string $address
 * @property string $state_id
 * @property string $city_id
 * @property string $enabled
 * @method static User join(...$parameters)
 * @method static User orderBy(...$parameters)
 * @method static User orderByDesc(...$parameters)
 * @method static User whereBetween(...$parameters)
 * @method static User role($parameter)
 * @method static User select(...$parameters)
 * @method static UserFactory factory(...$parameters)
 * @method static UserQueryBuilder query()
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_document',
        'number_document',
        'first_name',
        'second_name',
        'surname',
        'second_surname',
        'email',
        'password',
        'birthdate',
        'gender',
        'address',
        'phone',
        'state_id',
        'city_id',
        'enabled'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param Builder $query
     * @return UserQueryBuilder
     */
    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    /**
     * @var string
     */
    protected $guard_name = "web";

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }
}
