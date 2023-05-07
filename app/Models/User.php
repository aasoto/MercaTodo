<?php

namespace App\Models;

use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static UserQueryBuilder query()
 * @method static User select(...$parameters)
 * @method static User join(...$parameters)
 * @method static User orderBy(...$parameters)
 * @method static User role($parameter)
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
        // 'name',
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
     * Interact with the user's password.
     */
    // protected function password(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn (string $value) => Hash::make($value),
    //     );
    // }

    /**
     * @param Builder $query
     * @return UserQueryBuilder
     */
    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }
}
