<?php

namespace Tests\Feature\Http\Mail;

use App\Http\Mail\SendEmailReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendEmailReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_send_import_products_email_successfull(): void
    {
        $type_report = "Order's report";
        $mailable = new SendEmailReport($type_report, 'file.csv');

        $mailable->assertFrom(env('MAIL_FROM_ADDRESS'));
        $mailable->assertHasSubject($type_report.' file');
        $mailable->assertSeeInHtml($type_report.' list [Click on this card for download]');
    }
}
