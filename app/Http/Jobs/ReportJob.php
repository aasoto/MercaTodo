<?php

namespace App\Http\Jobs;

use App\Http\Mail\SendEmailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $email_subject,
        private mixed $user,
        private string $path,
    )
    {}

    public function handle(): void
    {
        Mail::to($this->user)->send(new SendEmailReport($this->email_subject, $this->path));
    }
}
