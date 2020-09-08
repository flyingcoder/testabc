<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendReminderJob;
use Carbon\Carbon;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        abort(404);
        //return view('home');
    }

    public function change()
    {
        request()->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = User::findOrFail(request()->id);

        $user->password = bcrypt(request()->password);

        $user->temp_password = 0;

        $user->save();

        return back()->with('success', true);
    }

    public function process()
    {
        request()->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $user = User::create([
            'name' => 'temp-user',
            'email' => request()->email,
            'password' => bcrypt(env('TEMP_PASSWORD'))
        ]);

        $job = (new SendReminderJob($user))
                    ->delay(Carbon::now()->addSeconds(2));

        dispatch($job);

        return back()->with('success', true);
    }

}
