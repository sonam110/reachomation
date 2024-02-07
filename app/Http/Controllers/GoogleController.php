<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Collection;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DashboardController;
use Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayementNotification;
class GoogleController extends Controller
{
    public function redirecttogoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handlecallback(Request $request) {
        //$user = Socialite::driver('google')->user();
        // dd($user);
        $db_column = 'oauth_id';
        $sUser = Socialite::driver('google')->stateless()->user();
        $finduser = User::where($db_column, $sUser->id)->where('email', $sUser->email)->first();
        $UserExists = User::where('email', $sUser->email)->first();
        if (empty($finduser) && empty($UserExists)) {
            $userdetails = new User;
            $userdetails->status = 3;
            $userdetails->name = $sUser->name;
            $userdetails->email = $sUser->email;
            $userdetails->password = Hash::make('password');
            $userdetails->oauth_id = $sUser->id;
            $userdetails->oauth_provider = 'Google';
            $userdetails->avatar = $sUser->avatar;
            $userdetails->save();

            createFreePackage($userdetails);

            
            if($userdetails){

                event(new Registered($userdetails));

                Auth::login($userdetails);

                /*-----------Create Default List----------*/
                $listArr = ['Revealed','Favourites'];
                foreach ($listArr as $key => $list) {
                    $collection = New Collection;
                    $collection->name = $list;
                    $collection->user_id = $userdetails->id;
                    $collection->status = '1';
                    $collection->save();
                }

                $content = [
                    'name' => $userdetails->name,

                ];
                if (env('IS_MAIL_ENABLE', false) == true) {
                    Mail::to($userdetails->email)->send(new WelcomeMail($content));
                }

                return redirect(RouteServiceProvider::HOME);
            }
            
        }else{
            
            $userdetails = User::where('email', $sUser->email)->first();
            $userdetails->oauth_id = $sUser->id;
            $userdetails->oauth_provider = 'Google';
            $userdetails->avatar = $sUser->avatar;
            $userdetails->save();
            if (!Auth::attempt(['email' => $sUser->email, 'password' => 'password'])) {
                $request->session()->regenerate();
            }
            Auth::login($userdetails);
            return redirect()->intended('dashboard');
        }
    }
    
}
