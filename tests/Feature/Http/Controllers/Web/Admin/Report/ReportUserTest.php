<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Report;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Carbon\Carbon;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ReportUserTest extends TestCase
{
    use RefreshDatabase;

    private User $user_admin, $user_client;
    private State $state;
    private City $city;
    private TypeDocument $type_document;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->seed([
            RoleSeeder::class,
        ]);

        $this->state = State::factory()->create();
        $this->city = City::factory()->create();
        $this->type_document = TypeDocument::factory()->create();

        $this->user_admin = User::factory()->create()->assignRole('admin');
        $this->user_client = User::factory()->create([
            'created_at' => '2023-07-01',
        ])->assignRole('client');
    }

    public function test_can_search_user_applaying_all_filters(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('user_report?search='.$this->user_client->first_name.'&typeDocument='.$this->type_document->code.'&verified=true&enabled=true&role=2&date1=2023-01-01&date2=2023-12-31&stateId=1&cityId=1');

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/User/Create')
                -> has('filters')
                -> has('users.data.0', fn (Assert $page) => $page
                    -> where('id', $this->user_client->id)
                    -> where('type_document', $this->type_document->code)
                    -> where('number_document', strval($this->user_client->number_document))
                    -> where('first_name', $this->user_client->first_name)
                    -> where('second_name', $this->user_client->second_name)
                    -> where('surname', $this->user_client->surname)
                    -> where('second_surname', $this->user_client->second_surname)
                    -> where('enabled', intval($this->user_client->enabled))
                    -> where('state_name', $this->state->name)
                    -> where('city_name', $this->city->name)
                    -> etc()
                )
                -> has('states')
                -> has('cities')
                -> has('typeDocuments')
                -> has('roles')
                -> has('success')
        );
    }

    public function test_can_search_disabled_users(): void
    {
        /**
         * @var User $disabled_user
         */
        $disabled_user = User::factory()->create([
            'enabled' => false,
        ])->assignRole('client');

        $response = $this->actingAs($this->user_admin)
            ->get('user_report?enabled=false');

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/User/Create')
                -> has('filters')
                -> has('users.data.0', fn (Assert $page) => $page
                    -> where('id', $disabled_user->id)
                    -> where('type_document', $this->type_document->code)
                    -> where('number_document', strval($disabled_user->number_document))
                    -> where('first_name', $disabled_user->first_name)
                    -> where('second_name', $disabled_user->second_name)
                    -> where('surname', $disabled_user->surname)
                    -> where('second_surname', $disabled_user->second_surname)
                    -> where('enabled', intval($disabled_user->enabled))
                    -> where('state_name', $this->state->name)
                    -> where('city_name', $this->city->name)
                    -> etc()
                )
                -> has('states')
                -> has('cities')
                -> has('typeDocuments')
                -> has('roles')
                -> has('success')
        );
    }

    public function test_can_search_user_with_unverfied_email(): void
    {
        /**
         * @var User $unverified_user
         */
        $unverified_user = User::factory()->create([
            'email_verified_at' => null,
        ])->assignRole('client');

        $response = $this->actingAs($this->user_admin)
            ->get('user_report?verified=false');

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/User/Create')
                -> has('filters')
                -> has('users.data.0', fn (Assert $page) => $page
                    -> where('id', $unverified_user->id)
                    -> where('type_document', $this->type_document->code)
                    -> where('number_document', strval($unverified_user->number_document))
                    -> where('first_name', $unverified_user->first_name)
                    -> where('second_name', $unverified_user->second_name)
                    -> where('surname', $unverified_user->surname)
                    -> where('second_surname', $unverified_user->second_surname)
                    -> where('enabled', intval($unverified_user->enabled))
                    -> where('state_name', $this->state->name)
                    -> where('city_name', $this->city->name)
                    -> etc()
                )
                -> has('states')
                -> has('cities')
                -> has('typeDocuments')
                -> has('roles')
                -> has('success')
        );
    }

    public function test_can_search_user_by_creation_date_1(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('user_report?date1='.Carbon::parse($this->user_client->created_at)->format('Y-m-d'));

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/User/Create')
                -> has('filters')
                -> has('users.data.0', fn (Assert $page) => $page
                    -> where('id', $this->user_client->id)
                    -> where('type_document', $this->type_document->code)
                    -> where('number_document', strval($this->user_client->number_document))
                    -> where('first_name', $this->user_client->first_name)
                    -> where('second_name', $this->user_client->second_name)
                    -> where('surname', $this->user_client->surname)
                    -> where('second_surname', $this->user_client->second_surname)
                    -> where('enabled', intval($this->user_client->enabled))
                    -> where('state_name', $this->state->name)
                    -> where('city_name', $this->city->name)
                    -> etc()
                )
                -> has('states')
                -> has('cities')
                -> has('typeDocuments')
                -> has('roles')
                -> has('success')
        );
    }

    public function test_can_search_user_by_creation_date_2(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('user_report?date2='.Carbon::parse($this->user_client->created_at)->format('Y-m-d'));

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/User/Create')
                -> has('filters')
                -> has('users.data.0', fn (Assert $page) => $page
                    -> where('id', $this->user_client->id)
                    -> where('type_document', $this->type_document->code)
                    -> where('number_document', strval($this->user_client->number_document))
                    -> where('first_name', $this->user_client->first_name)
                    -> where('second_name', $this->user_client->second_name)
                    -> where('surname', $this->user_client->surname)
                    -> where('second_surname', $this->user_client->second_surname)
                    -> where('enabled', intval($this->user_client->enabled))
                    -> where('state_name', $this->state->name)
                    -> where('city_name', $this->city->name)
                    -> etc()
                )
                -> has('states')
                -> has('cities')
                -> has('typeDocuments')
                -> has('roles')
                -> has('success')
        );
    }

    public function test_can_search_user_by_state(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('user_report?stateId='.$this->state->id);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/User/Create')
                -> has('filters')
                -> has('users.data.0', fn (Assert $page) => $page
                    -> where('id', $this->user_admin->id)
                    -> where('type_document', $this->type_document->code)
                    -> where('number_document', strval($this->user_admin->number_document))
                    -> where('first_name', $this->user_admin->first_name)
                    -> where('second_name', $this->user_admin->second_name)
                    -> where('surname', $this->user_admin->surname)
                    -> where('second_surname', $this->user_admin->second_surname)
                    -> where('enabled', intval($this->user_admin->enabled))
                    -> where('state_name', $this->state->name)
                    -> where('city_name', $this->city->name)
                    -> etc()
                )
                -> has('states')
                -> has('cities')
                -> has('typeDocuments')
                -> has('roles')
                -> has('success')
        );
    }

    public function test_can_search_user_by_city(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('user_report?cityId='.$this->city->id);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/User/Create')
                -> has('filters')
                -> has('users.data.0', fn (Assert $page) => $page
                    -> where('id', $this->user_admin->id)
                    -> where('type_document', $this->type_document->code)
                    -> where('number_document', strval($this->user_admin->number_document))
                    -> where('first_name', $this->user_admin->first_name)
                    -> where('second_name', $this->user_admin->second_name)
                    -> where('surname', $this->user_admin->surname)
                    -> where('second_surname', $this->user_admin->second_surname)
                    -> where('enabled', intval($this->user_admin->enabled))
                    -> where('state_name', $this->state->name)
                    -> where('city_name', $this->city->name)
                    -> etc()
                )
                -> has('states')
                -> has('cities')
                -> has('typeDocuments')
                -> has('roles')
                -> has('success')
        );
    }

    public function test_can_queue_report_of_users(): void
    {
        Excel::fake();

        $time = strval(time());

        $response = $this->actingAs($this->user_admin)
            ->post(route('user.report.export'), [
                'search' => $this->user_client->first_name,
                'time' => $time,
            ]);

        Excel::assertQueued('reports/users/users_'.$time.'.xlsx');

        $response->assertRedirect(route('user.report.create'))
        ->assertSessionHasAll(['success' => 'Users report generated.']);
    }
}
