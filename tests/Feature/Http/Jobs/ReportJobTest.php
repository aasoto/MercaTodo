<?php

namespace Tests\Feature\Http\Jobs;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Http\Jobs\ReportJob;
use App\Http\Mail\SendEmailReport;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ReportJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_send_report_email(): void
    {
        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        Mail::fake();

        (new ReportJob(
            '',
            User::factory()->create()->assignRole('admin'),
            '')
        )->handle();

        Mail::assertSent(SendEmailReport::class);

    }
}
