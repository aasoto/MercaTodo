<?php
namespace App\Traits;

use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection as Collection;
use Spatie\Permission\Models\Role;

/**
 * Save and take data from the cache
 */
trait useCache
{
    public function getCities(): Collection
    {
        if (Cache::has('cities')) {
            return Cache::get('cities');
        } else {
            $cities = City::select('id', 'name', 'state_id')->get();
            Cache::put('cities', $cities);
            return $cities;
        }
    }

    public function getRoles(): Collection
    {
        if (Cache::has('roles')) {
            return Cache::get('roles');
        } else {
            $roles = Role::select('id', 'name')->get();
            Cache::put('roles', $roles);
            return $roles;
        }
    }

    public function getStates(): Collection
    {
        if (Cache::has('states')) {
            return Cache::get('states');
        } else {
            $states = State::select('id', 'name')->get();
            Cache::put('states', $states);
            return $states;
        }
    }

    public function getTypeDocument(): Collection
    {
        if (Cache::has('type_documents')) {
            return Cache::get('type_documents');
        } else {
            $type_documents = TypeDocument::select('id', 'code', 'name')->get();
            Cache::put('type_documents', $type_documents);
            return $type_documents;
        }
    }
}
