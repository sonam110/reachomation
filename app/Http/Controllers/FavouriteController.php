<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BlogSearchData;
use App\Models\EmailCollection;

class FavouriteController extends Controller
{
    public function index(Request $request){
        $conn = DB::connection('blogsearch');
        $favourites = DB::table('favourites')
                    ->select('domain_id')
                    ->where('user_id', $request->user()->id)
                    ->get();

        $domain = array();
        foreach($favourites as $favourite){
            $domain[] = $favourite->domain_id;
        }
        /*$sites = $conn->table("blog_search_data")
                ->join('blog_emails', 'blog_emails.domain_id', '=', 'blog_search_data.domain_id')
                ->join('blog_social_data', 'blog_social_data.domain_id', '=', 'blog_search_data.domain_id')
                ->join('domain_detail', 'domain_detail.domain_id', '=', 'blog_search_data.domain_id')
                ->select()
                ->whereIn('blog_search_data.domain_id', $domain)
                ->paginate(10);*/
        // $sites->withPath(url()->full());

         $sites =  DB::table('website_data')
                ->whereIn('website_data.domain_id', $domain)
                ->paginate(10);

        $totalCount = DB::table('website_data')
                ->whereIn('website_data.domain_id', $domain)->count();


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

        $hiddens = DB::table('hiddens')
                    ->where('user_id', $request->user()->id)
                    ->get(); 

        $list_count = DB::table('collections')
                    ->where('user_id', $request->user()->id)
                    ->where('status', '=' ,1)
                    ->count();

       
        return view('favourites', ['sites'=>$sites, 'favourites'=>$favourites, 'collections'=>$collections, 'collectionsData'=>$collectionsData, 'hiddens'=>$hiddens, 'list_count'=>$list_count, 'totalCount'=>$totalCount]);
    }
}
