<?php

namespace App\Jobs;

use App\Mail\ConfirmationEmail;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailTemplates;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class SendConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    protected $settings;
    protected $template;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->settings = Setting::first();
        $this->template = EmailTemplates::where('id', 1)->first();
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try{
          Mail::to($this->user->email)->send(new ConfirmationEmail($this->user, $this->settings, $this->template));
        }
        catch(\Exception $e){
            error_log($e->getMessage());
        }
            
    }
}
