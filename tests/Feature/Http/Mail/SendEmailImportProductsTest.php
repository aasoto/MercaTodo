<?php

namespace Tests\Feature\Http\Mail;

use App\Http\Mail\SendEmailImportProducts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendEmailImportProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_send_import_products_email_successfull(): void
    {
        $mailable = new SendEmailImportProducts(true);

        $mailable->assertFrom(env('MAIL_FROM_ADDRESS'));
        $mailable->assertHasSubject('Send Email Status Import Products');
        $mailable->assertSeeInHtml('Products list successfully imported.');
    }

    public function test_can_send_import_products_email_failed(): void
    {
        $mailable = new SendEmailImportProducts(false);

        $mailable->assertFrom(env('MAIL_FROM_ADDRESS'));
        $mailable->assertHasSubject('Send Email Status Import Products');
        $mailable->assertSeeInHtml('Products list imported has failed.');
    }
}
