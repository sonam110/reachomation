<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Collection;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Cookie;
use Illuminate\Support\Facades\Crypt;
use Mail;
use App\Mail\WelcomeMail;
use App\Rules\IsValidPassword;
use Validator;
use Stripe;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayementNotification;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:25'],
           // 'phone' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],

            
        ],
                    
        [
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must be at least 8 characters and contain at least 1 uppercase character, 1 number, and 1 special character',
        ]);

        
        $token = \Str::random(64);
        $user = User::create([
            'name' => $request->name,
            //'phone' => $request->phone,
            'email' => $request->email,
            'email_verified_token' => $token,
            'password' => Hash::make($request->password),
            // 'credits' => 25000,
            'status' => '2',
        ]);
        // Cookie::queue('welcome', 1, 1440);
        
        event(new Registered($user));

        //Auth::login($user);

        createFreePackage($user);
        /*-----------Create Default List----------*/
        $listArr = ['Revealed','Favourites'];
        foreach ($listArr as $key => $list) {
            $collection = New Collection;
            $collection->name = $list;
            $collection->user_id = $user->id;
            $collection->status = '1';
            $collection->save();
        }
        $content = [
                'name' => $request->name,
                'verify_link' => env('APP_URL') . '/email-verification/' . $user->id . '/' . $user->email_verified_token
            ];
        if (env('IS_MAIL_ENABLE', false) == true) {
            Mail::to($request->email)->send(new EmailVerificationMail($content));
        }

        return redirect()->back()
                ->with('success', 'Verification link has been sent to your registered email id');
    }

    public function subscribePlan(Request $request){
        $plan = $request->plan;

        $user = DB::table('users')
                    ->where('google_id', $googleid[0])
                    ->update(['company' => $request->company, 'whatsapp' => $request->whatsapp]);
        
        return redirect('register');
    }

}
