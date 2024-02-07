<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\EmailCollection;
use App\Models\User;
use DB;
use Auth;
use Session;
use Validator;
use Exception;
use Illuminate\Support\Facades\Hash;
class SettingsController extends Controller
{
	
    public function settings (Request $request){
        

    	$emailCollection = EmailCollection::where('user_id',auth()->user()->id)->where('email','!=','')->where('status','!=','2')->get();
    	$countries = DB::table('user_country')->get();
    	$niches = DB::table('niches_keyword')->get();

        $country_id = DB::table('user_country')->where('shortcode',auth()->user()->country)->first();
        $countryId = (!empty($country_id)) ? $country_id->id : null;
        $statsList = DB::table('states')->where('country_id',$countryId)->get();
        
    	return view('settings', compact('countries','emailCollection','niches','statsList'));
    }
    public function getauthurl(){
        $authUrl = $this->client->createAuthUrl();
        return $authUrl;
    }

    public function updateProfile(Request $request)
    {
        /*$this->validate($request, [
            'niches'              => 'required',
            'phone'               => 'required|numeric',
            'company'             => 'required',
            'country'             => 'required',
            'state'               => 'required',
            'city'                => 'required',
            'address'             => 'required',
            'postal_code'         => 'required',
            'skype'               => 'required',
        ]);*/
       
        $user = User::find(auth()->user()->id);
        $user->niches = implode(',',$request->niches);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->company = $request->company;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->line1 = $request->address;
        $user->postal_code = $request->postal_code;
        $user->skype = $request->skype;
        $user->is_email_notify = ($request->is_email_notify == true) ? $request->is_email_notify:0;
        $user->save();
        
        return redirect()->back()->with('success', 'Profile updated successfully');
        
        
    }

     public function userSavePassword(Request $request)
    {
        $this->validate($request, [
            'old_password'              => ['required'],
            'new_password'              => ['required', 'confirmed', 'min:8', 'max:25'],
            'new_password_confirmation' => ['required']
        ]);

        $matchpassword  = User::find(Auth::user()->id)->password;
        if(\Hash::check($request->old_password, $matchpassword))
        {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->back()->with('success', 'Password successfully changed.');
            
            
        }
        else
        {
            return redirect()->back()->with('error, Incorrect password, Please try again with correct password.');
        }
        
    }

    public function getState(Request $request)
    {
       
        $statsList = DB::table('states')->where('country_id',$request->country_id)->get();
        $output = '';
        $selected = '';
        $output .='<select class="form-select shadow-none country" id="floatingSelect" aria-label="Floating label select example" name="country"><option value="" selected> Select State</option>';
              foreach($statsList as $state) {
                $output .='<option  value="'.$state->name.'"  >
                    '.ucfirst($state->name) .'
                </option>';
                }
           $output .=' </select>';

        return $output;
        
        
    }

    
}
