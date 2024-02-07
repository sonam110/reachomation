<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MessageListService;
use App\Models\Template;
use App\Models\Revealed;
use App\Models\EmailCollection;
use App\Models\SubscriptionPlan;
use App\Models\Userlog;
use App\Models\Campaign;
use Auth;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index(Request $request){

        //dd(Carbon::now()->setTimezone('America/New_York')->format('Y-m-d H:i:s'));
        $revealedDomains = Revealed::where('user_id', auth()->user()->id)->pluck('domain_id');
        $niches = explode(",", $request->user()->niches);
        foreach($niches as $niche){
            $first = 1;
            $websites = DB::table('website_data')
                    ->select()
                    //->whereNotIn('domain_id', $revealedDomains)
                    ->where('credit_cost','>','0')
                    ->where(function ($query) use($niche) {
                        $query->where('credit_cost','>','0')
                        ->where('website', 'LIKE', '%'.$niche.'%')
                        ->orWhere('title', 'LIKE', '%'.$niche.'%')
                        ->orWhere('description', 'LIKE', '%'.$niche.'%')
                        ->orWhere('anchor', 'LIKE', '%'.$niche.'%');
                    })
                    
                    //->inRandomOrder()
                    ->paginate(10);

            // $websites = $con->table('blog_search_data')
            //         ->join('blog_emails', 'blog_emails.domain_id', '=', 'blog_search_data.domain_id')
            //         ->join('blog_social_data', 'blog_social_data.domain_id', '=', 'blog_search_data.domain_id')
            //         ->join('domain_detail', 'domain_detail.domain_id', '=', 'blog_search_data.domain_id')
            //         ->select()
            //         ->where('blog_search_data.anchor', 'LIKE', '%'.$niche.'%')
            //         ->take(2)
            //         ->get();

            $nicheData[$niche] = $websites;
            $first = 0;
        }

        // dd($websites);

        $niches = DB::table('niches_keyword')
                ->select('niche')
                ->get();

        $favourites = DB::table('favourites')
                    ->select('domain_id')
                    ->where('user_id', $request->user()->id)
                    ->get();
        
        $collections = DB::table('collections')
                        ->select()
                        ->where('user_id', $request->user()->id)
                        ->where('status', '=' ,1)
                        ->whereNotIn('name', ['Revealed','Favourites'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        $collectionsData = DB::table('domain_collections')
                        ->select()
                        ->where('user_id', $request->user()->id)
                        ->get();
        
        $list_count = DB::table('collections')
                    ->where('user_id', $request->user()->id)
                    ->where('status', '=' ,1)
                    ->count();

        $campaign_count = DB::table('campaigns')
                    ->where('user_id', $request->user()->id)
                    ->where('status', '=' ,1)
                    ->count();

        $hiddens = DB::table('hiddens')
                    ->where('user_id', $request->user()->id)
                    ->get();

        $revealeds = DB::table('revealeds')
                    ->where('user_id', $request->user()->id)
                    ->get();

        
        
          $default_template = Template::where('user_id', auth()->user()->id)
                ->where('id', $request->user()->default_tid)
                ->where('status', '=' ,1)
                ->first();
        
        return view('dashboard', ['nicheData'=>$nicheData,'niches'=>$niches, 'favourites'=>$favourites, 'collections'=>$collections, 'list_count'=>$list_count, 'collectionsData'=>$collectionsData, 'hiddens'=>$hiddens, 'campaign_count'=>$campaign_count, 'revealeds'=>$revealeds, 'default_template'=>$default_template]);
        
    }

    

    public function transactionHistory(){
        $transaction_histories = DB::table('transaction_histories')->where('user_id',auth()->user()->id)->where('payment_for','1')->orderby('created_at','DESC')->paginate(10);
        return view('transaction_histories',compact('transaction_histories'));
    }


   
}
