<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendChangePwEmail;
use Mail;
use App\User;

class SendReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user = null)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->user == null) {
            
            $users = User::where('temp_password', true)
                           ->where('counter', '>', 0)
                           ->get();

            foreach ($users as $key => $user) {
                $user->counter = $user->counter - 1;
                $user->save();
                Mail::to($user->email)->send(new SendChangePwEmail($user->id));
            }
        } else {
            Mail::to($this->user->email)->send(new SendChangePwEmail($this->user->id));
        }
    }
}
