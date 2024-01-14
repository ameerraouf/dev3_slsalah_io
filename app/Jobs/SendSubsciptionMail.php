<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SubscribeMail;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailTemplates;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendSubsciptionMail implements ShouldQueue
{
    protected $user;
    protected $settings;
    protected $template;
  
    
    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user ;
        $this->settings = Setting::first();
        $this->template = EmailTemplates::where('id', 5)->first();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
        Mail::to($this->user->email)->send(new SubscribeMail($this->user, $this->settings, $this->template));    
        }
         catch(\Exception $e){
            error_log($e->getMessage());
        }
    }
}
