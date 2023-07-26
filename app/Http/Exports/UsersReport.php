<?php

namespace App\Http\Exports;

use App\Domain\User\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersReport implements FromQuery, WithHeadings
{
    use Exportable;

    /**
     * @param array<mixed> $filters
     */
    public function __construct(
        private array $filters,
    )
    {}

    public function query()
    {
        /**
         * @var Builder|EloquentBuilder|Relation $query;
         */
        $query = User::query()
        -> whereSearch(isset($this->filters['search']) ? $this->filters['search'] : null)
        -> whereTypeDocument(isset($this->filters['type_document']) ? $this->filters['type_document'] : null)
        -> whereEnabled(isset($this->filters['enabled']) ? $this->filters['enabled'] : null)
        -> whereRole(isset($this->filters['role']) ? $this->filters['role'] : null)
        -> whereEmailVerified(isset($this->filters['verified']) ? $this->filters['verified'] : null)
        -> whereBetweenCreatedAt(
            isset($this->filters['date_1']) ? $this->filters['date_1'] : null,
            isset($this->filters['date_2']) ? $this->filters['date_2'] : null,
        )
        -> whereStateAndCity(
            isset($this->filters['state_id']) ? $this->filters['state_id'] : null,
            isset($this->filters['city_id']) ? $this->filters['city_id'] : null,
        )
        -> select(
            'users.id',
            'users.type_document',
            'users.number_document',
            'users.first_name',
            'users.second_name',
            'users.surname',
            'users.second_surname',
            'users.email_verified_at',
            'users.enabled',
            'states.name as state_name',
            'cities.name as city_name',
            'model_has_roles.role_id',
            'users.created_at',
        )
        -> join('states', 'users.state_id', 'states.id')
        -> join('cities', 'users.city_id', 'cities.id')
        -> join('type_documents', 'users.type_document', 'type_documents.code')
        -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
        -> orderBy('users.id');

        return $query;
    }

    /**
     * @return array<mixed>
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tipo de documento',
            'Número de documento',
            'Primer nombre',
            'Segundo nombre',
            'Primer apellido',
            'Segundo apellido',
            'Fecha de verificación',
            'Habilitado',
            'Estado',
            'Ciudad',
            'Rol',
            'Fecha de creación',
        ];
    }

    public function prepareRows(mixed $rows): mixed
    {
        return $rows->transform(function ($user) {

            switch ($user->role_id) {
                case 1:
                    $user->role_id = 'Administrador';
                    break;
                case 2:
                    $user->role_id = 'Cliente';
                    break;
                default:
                    $user->role_id = 'Desconocido';
                    break;
            }

            return $user;
        });
    }
}
