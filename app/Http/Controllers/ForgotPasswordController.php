<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;
use Exception;
use Mail;
use App\Models\User;
use App\Mail\forgetpassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Carbon;
class ForgotPasswordController extends Controller
{
    public function forgetPassword()
    {

        return view('auth.forgot-password');
    }
    
    public function fPassword(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                    'email'          => 'required|email|exists:users,email', 
            ]);
              if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
            $user = User::where('email',$request->email)->first();
            if (!$user) {
               return redirect()->back()
                    ->with('error', 'User Not found.');
            }
            if(in_array($user->status, [0,2,4])) {
                 return redirect()->back()
                    ->with('error', 'Your account is inactive please contact to admin.');
                 
            }
            if($user->oauth_provider=='Google'){
                return redirect()->back()
                    ->with('error', 'You are not authorize to do this action .');
            }
            
            //Delete if entry exists
            DB::table('password_resets')->where('email', $request->email)->delete();

            $token = \Str::random(64);
            DB::table('password_resets')->insert([
              'email' => $request->email, 
              'token' => $token, 
              'created_at' => Carbon::now()
            ]);
           
            $content = ([
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token,

            ]);         
                 
             Mail::to($request->email)->send(new forgetpassword($content));
          
            return redirect()->back()
                ->with('success', 'Password Reset link has been sent to your registered email id');

            
        }
        catch(Exception $exception) {
            //dd($exception->getMessage());
             return redirect()->back()
                    ->with('error', $exception->getMessage());
            
        }
    }
    public function passwordRes($token)
    {
        return view('reset-password',compact('token'));
    }
    public function passwordReset(Request $request)
    {
        try {
            $input = $request->only('token', 'password', 'password_confirmation');
            $validator = Validator::make($input, [
                'token' => 'required',
                'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
                'password_confirmation' => 'required',
            ],   
            [
                'password.min' => 'Password must be at least 8 characters',
                'password.regex' => 'Password must be at least 8 characters and contain at least 1 uppercase character, 1 number, and 1 special character',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $tokenExist = DB::table('password_resets')
                ->where('token', $request->token)
                ->first();
            if (!$tokenExist) {
                return redirect()->back()
                    ->with('error', 'Invalid Token.');
            }
           
            $user = User::where('email',$tokenExist->email)->first();
            if (!$user) {
                return redirect()->back()
                    ->with('error', 'User Not found.');
            }
            if(in_array($user->status, [0,2,4])) {
                 return prepareResult(false, 'Your account is inactive please contact to admin.', [], $this->unauthorized);
            }

            $user = User::where('email', $tokenExist->email)
                    ->update(['password' => Hash::make($request->password)]);
 
            DB::table('password_resets')->where(['email'=> $tokenExist->email])->delete();

            return redirect()->back()
                ->with('success', 'Password Reset successfully');
        }
         catch(Exception $exception) {
             return redirect()->back()
                    ->with('error', 'Oops!!!, something went wrong, please try again.');
            
        }
    }
}
