<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\Userlog;
class IsSubscriptionExpire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */


    public function handle(Request $request, Closure $next)
    {
        if (\Auth::check()) {
           
            if(auth()->user()->plan !=''  && date('Y-m-d',strtotime(auth()->user()->plan_expired_at)) < date('Y-m-d')){
                if(auth()->user()->plan !='1'){
                    return redirect(route('pricing'));

                }
            }
            
        }
        if (\Auth::check()) {
            $plan = SubscriptionPlan::where('id',auth()->user()->plan)->first();
            $userLogCount = Userlog::where('userId',auth()->user()->id)->where('status','1')->count();
            if($userLogCount > $plan->parallel_users){
                $email =   base64url_encode(auth()->user()->email);
                return redirect()->route('to-many-account',['email'=> $email]);
                
            }
        }

        return $next($request);
    }
}
