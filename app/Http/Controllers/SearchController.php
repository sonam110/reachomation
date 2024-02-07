<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Collection;
use App\Models\DomainCollection;
use Carbon\Carbon;
use App\Models\Revealed;
use App\Models\Template;
use App\Models\SubscriptionPlan;
use App\Models\Favourite;
use Helper;
use App\Models\EmailCollection;
class SearchController extends Controller
{
    public function index(Request $request){
        \DB::enableQueryLog();
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $searchString = explode(',', $request->keyword);

            $site = DB::table('website_data')
                    ->select('*')
                   ->selectRaw('MATCH(website,title,description,anchor) AGAINST(? IN BOOLEAN MODE) AS relevance',[$searchString])
                   ->orderBy('relevance', 'desc');

            if(isset($request->da) && is_null($request->da)==false){
                $site->where('da', '>=', $request->da);
            }
            if(isset($request->tf) && is_null($request->tf)==false){
                $site->where('tf', '>=', $request->tf);
            }
            if(isset($request->sot) && is_null($request->sot)==false){
                $site->where('sot', '>=', $request->sot);
            }
            if(isset($request->sok) && is_null($request->sok)==false){
                $site->where('sok', '>=', $request->sok);
            }
            if(isset($request->domain) && is_null($request->domain)==false){
                $site->whereIn('ext', $request->domain);
            }
            if(isset($request->updation) && is_null($request->updation)==false){
                if($request->updation=='all'){
                    $site->whereBetween('activity_date', [Carbon::now()->subMonth(36), Carbon::now()]);
                    $updation = 36;
                }else{
                    $site->whereBetween('activity_date', [Carbon::now()->subMonth($request->updation), Carbon::now()]);
                }
                
            }

            $sites = $site->where('credit_cost','>','0')->paginate(10);
            $sites->withPath(url()->full());

        }
        //dd(\DB::getQueryLog());

        if(isset($request->list)){
            $list = $request->list;
            $site = DB::table('website_data')
                    ->join('domain_collections', 'website_data.domain_id', '=', 'domain_collections.domain_id')
                    ->join('collections', 'domain_collections.collection_id', '=', 'collections.id')
                    ->select('website_data.*', 'collections.name');

            if(isset($request->da) && is_null($request->da)==false){
                $site->where('website_data.da', '>=', $request->da);
            }
            if(isset($request->tf) && is_null($request->tf)==false){
                $site->where('website_data.tf', '>=', $request->tf);
            }
            if(isset($request->sot) && is_null($request->sot)==false){
                $site->where('sot', '>=', $request->sot);
            }
            if(isset($request->sok) && is_null($request->sok)==false){
                $site->where('sok', '>=', $request->sok);
            }
            if(isset($request->domain) && is_null($request->domain)==false){
                $site->whereIn('website_data.ext', $request->domain);
            }
            if(isset($request->updation) && is_null($request->updation)==false){
                if($request->updation=='all'){
                    $site->whereBetween('website_data.activity_date', [Carbon::now()->subMonth(36), Carbon::now()]);
                    $updation = 36;
                }else{
                    $site->whereBetween('website_data.activity_date', [Carbon::now()->subMonth($request->updation), Carbon::now()]);
                }
                
            }
            $site->where('domain_collections.user_id', $request->user()->id);
            $site->where('domain_collections.collection_id', '=', $list);

            $sites = $site->where('credit_cost','>','0')->paginate(10);
            $sites->withPath(url()->full());
        }
        

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

        return view('search', ['sites'=>$sites, 'favourites'=>$favourites, 'collections'=>$collections, 'collectionsData'=>$collectionsData, 'hiddens'=>$hiddens, 'list_count'=>$list_count, 'revealeds'=>$revealeds,'default_template'=> $default_template]);
    }

    public function domainSearch(Request $request){
        $keyword = 'techpluto.com';

        $websites = DB::table('website_data')->select('domain_id','website','title','description','da','tf','sot','sok','activity_date')
        ->where('website', '=', $keyword)
        ->get();
        
        return view('front-pages.websites', ['websites'=>$websites]);
    }

    public function addDomaintolist(Request $request){
        $searchString = explode(',', $request->keyword);

        $sites = DB::table('website_data')
            ->select('domain_id','website')
            ->selectRaw('MATCH(website,title,description,anchor) AGAINST(? IN BOOLEAN MODE) AS relevance',[$searchString])
            ->whereNotIn('domain_id', DB::table('revealeds')->where('user_id', '=', $request->user()->id)->pluck('domain_id'))
            ->orderBy('relevance', 'desc')
            ->limit($request->size)
            ->get();
        //dd($sites);
        $domain = array();
        foreach($sites as $site){
            $domain[] = $site->domain_id;
        }
        $credits = DB::table('website_data')
                 ->whereIn('domain_id', $domain)
                 ->sum('credit_cost');
        
        if($credits > $request->user()->credits){
            return response()->json(['credits'=>$credits, 'credit_left' => $request->user()->credits]);
        }else{
            if($request->newlist!=''){
                $id = DB::table('collections')->insertGetId(
                ['user_id' => $request->user()->id, 'name' => $request->newlist, 'status' => 1, 'created_at' => Now(), 'updated_at' => Now()]);
                
                foreach($sites as $site){
                    DB::table('domain_collections')->insert([
                        'user_id' => $request->user()->id,
                        'collection_id' => $id,
                        'domain_id' => $site->domain_id,
                        'domain' => $site->website,
                        'created_at' => Now(),
                        'updated_at' => Now()
                    ]);
                }

                $list = DB::table('collections')
                        ->select('website_count')
                        ->where('user_id', $request->user()->id)
                        ->where('id', $id)
                        ->get();
                foreach($list as $lis){
                    $count = $lis->website_count;
                }
                if($count<1000){
                    $size = DB::table("domain_collections")
                        ->select()
                        ->where('user_id', $request->user()->id)
                        ->where('collection_id', '=' ,$id)
                        ->count();
                    $collections = DB::table('collections')
                            ->where('id', $request->collection_id)
                            ->update(['website_count' => $size]);
                }else{
                    return '4';
                }

            }else{
                foreach($sites as $site){
                    DB::table('domain_collections')->insert([
                        'user_id' => $request->user()->id,
                        'collection_id' => $request->list,
                        'domain_id' => $site->domain_id,
                        'domain' => $site->website,
                        'created_at' => Now(),
                        'updated_at' => Now()
                    ]);
                }

                $list = DB::table('collections')
                        ->select('website_count')
                        ->where('user_id', $request->user()->id)
                        ->where('id', $request->list)
                        ->get();
                foreach($list as $lis){
                    $count = $lis->website_count;
                }
                if($count<1000){
                    $size = DB::table("domain_collections")
                        ->select()
                        ->where('user_id', $request->user()->id)
                        ->where('collection_id', '=' ,$request->list)
                        ->count();
                    $collections = DB::table('collections')
                            ->where('id', $request->list)
                            ->update(['website_count' => $size]);
                }else{
                    return '4';
                }

            }
            if($request->reveal_or_not  =='2') {
                foreach($sites as $site){
                    DB::table('revealeds')->insert([
                        'user_id' => $request->user()->id,
                        'domain_id' => $site->domain_id,
                        'created_at' => Now(),
                        'updated_at' => Now()
                    ]);
                }
                $updatecredit = DB::table('users')
                            ->where('id', $request->user()->id)
                            ->update(['credits' => $request->user()->credits-$credits, 'updated_at' => Now()]);

                if($updatecredit){
                    return '2';
                }else{
                    return '3';
                }
           } else{
            return '2';
           }
        }

    }

    public function getDomainList(Request $request){
       try {
           $data = $request->all();
           $user = auth()->user();
           if(isset($data)){
                if($request->list_type =='2'){
                    $revealedDomains = Favourite::where('user_id', $user->id)
                    ->pluck('domain_id');
                } else{
                    $revealedDomains = Revealed::where('user_id', $user->id)->pluck('domain_id');
                }
                $keyword = @$data['keyword'];
                $searchString = explode(',', @$data['keyword']);
                $search_keyword = @$data['keyword'];

                $site = DB::table('website_data')
                        ->select('*')
                       ->selectRaw('MATCH(website,title,description,anchor) AGAINST(? IN BOOLEAN MODE) AS relevance',[$searchString])
                        ->orderBy('relevance', 'desc');
                /*if(isset($data['keyword']) && is_null($data['keyword'])==false ){
                    $site->where('website', 'LIKE', "%{$search_keyword}%")->orWhere('title', 'LIKE', "%{$search_keyword}%")->orWhere('description', 'LIKE', "%{$search_keyword}%")->orWhere('anchor', 'LIKE', "%{$search_keyword}%");
                
                }*/
                if(isset($data['da']) && is_null($data['da'])==false ){
                    $site->where('da', '>=', $data['da']);
                }
                if(isset($data['tf']) && is_null($data['tf'])==false ){
                    $site->where('tf', '>=', $data['tf']);
                }
                if(isset($data['sot']) && is_null($data['sot'])==false ){
                    $site->where('sot', '>=', $data['sot']);
                }
                if(isset($request->sok) && is_null($request->sok)==false){
                    $site->where('sok', '>=', $request->sok);
                }
                if(isset($data['domain']) && is_null($data['domain'])==false ){
                    $domain = explode(',', $data['domain']);
                    $site->whereIn('ext', $domain);
                }
                if(isset($data['updation']) && is_null($data['updation'])==false ){
                    if($data['updation']=='all'){
                        $site->whereBetween('activity_date', [Carbon::now()->subMonth(36), Carbon::now()]);
                        $updation = 36;
                    }else{
                        $site->whereBetween('activity_date', [Carbon::now()->subMonth($data['updation']), Carbon::now()]);
                    }
                    
                }
                $reveal_or_not = ($request->reveal_or_not !='') ? $request->reveal_or_not:'1';
                $size = (isset($data['size'])) ? $data['size'] : 50;
                if($request->reveal_or_not =='1'){
                    $sites = $site->whereIn('domain_id', $revealedDomains)->limit($size)->get();
                } else{
                    $sites = $site->where('credit_cost','>','0')->whereNotIn('domain_id', $revealedDomains)->limit($size)->get();
                }
                
                
            }
            return view('load-list-domain',compact('sites','size','reveal_or_not'));
        }
        catch(Exception $exception) {
            
            return response()->json(['type'=> 'failed']);
            
        }  
    }

    public function adSitesTolist(Request $request){
       DB::beginTransaction();
        try {

            $credits  = $request->credit_need; 
            $domains =  explode(',', $request->domain_ids);
            $plan = SubscriptionPlan::where('id',auth()->user()->plan)->first();
            if(empty($plan)){
                return response()->json(['type'=> 'error','message'=>'You can not upload a file! you have no purchase plan']);
            }
            if($request->domain_ids!='') {
                if($request->reveal_or_not =='2'){
                    if($credits > $request->user()->credits){
                        return response()->json(['credits'=>$credits, 'credit_left' => $request->user()->credits,'type'=> 'creditlow']);
                    }
                }
                if($request->new_list_name!=''){

                    $checkAlready = Collection::where('user_id',$request->user()->id)->where('name',ucfirst($request->new_list_name))->first();
                    if(is_object($checkAlready)){
                        return response()->json(['type'=> 'error','message'=>'List name already exists']);
                    }
                    $newCollection = new Collection;
                    $newCollection->user_id = auth()->user()->id;
                    $newCollection->name = $request->new_list_name;
                    $newCollection->website_count = 0;
                    $newCollection->status = 1;
                    $newCollection->save();
                    $id = $newCollection->id;
                     DB::commit();
                    
                }else{
                    $id = $request->created_list;
                }
               
              
                $list = DB::table('collections')->select('website_count','id','name')->where('user_id', auth()->user()->id)->where('id', $id)->first();
                
                $count = (!empty($list)) ? $list->website_count :0 ;
                
                if($count > $plan->size_limit){
                    return response()->json(['count'=>$count,'type'=> 'error','message'=>'Limit Exceeded Max '.$plan->size_limit.' domain']);
                }

                $reveal_list = DB::table('collections')
                        ->where('user_id', auth()->user()->id)
                        ->where('name','Revealed')
                        ->first();

                foreach($domains as $domain){
                    $site = DB::table('website_data')->select('domain_id','website')->where('domain_id',$domain)->first();
                    DB::table('domain_collections')->insert([
                        'user_id' => $request->user()->id,
                        'collection_id' => $id,
                        'domain_id' => $domain,
                        'domain' => $site->website,
                        'created_at' => Now(),
                        'updated_at' => Now()
                    ]);

                    
                    if($request->reveal_or_not =='2'){
                        /*--Add to Reveal List -------------*/
                        
                        if(!empty($reveal_list)){       
                            DB::table('domain_collections')->insert([
                                'user_id' => $request->user()->id,
                                'collection_id' => $reveal_list->id,
                                'domain_id' => $domain,
                                'domain' => $site->website,
                                'created_at' => Now(),
                                'updated_at' => Now()
                            ]);
                        }

                        

                        DB::table('revealeds')->insert([
                            'user_id' => $request->user()->id,
                            'domain_id' => $domain,
                            'created_at' => Now(),
                            'updated_at' => Now()
                        ]);
                    }
                }
                if($request->reveal_or_not =='2'){
                    $checkRevealSize = DB::table("domain_collections")->where('user_id',auth()->user()->id)->where('collection_id', '=' ,@$reveal_list->id)->count();
                    $collectionscount = DB::table('collections')->where('id',@$reveal_list->id)->update(['website_count' => $checkRevealSize]);
                }

                $size = DB::table("domain_collections")->where('user_id',auth()->user()->id)->where('collection_id', '=' ,$id)->count();
                $collections = DB::table('collections')->where('id',$id)->update(['website_count' => $size]);
                   

                
                if($request->reveal_or_not =='2'){

                    Helper::transactionHistory($request->user()->id,'2','1','0',$credits,'Revealed '.count($domains).' sites [Added to '.$list->name.']','1');

                    $updatecredit = DB::table('users')
                                ->where('id', $request->user()->id)
                                ->update(['credits' => $request->user()->credits-$credits, 'updated_at' => Now()]);
                  
                     DB::commit();

                    if($updatecredit){
                       return response()->json(['type'=> 'success']);
                    }else{
                       return response()->json(['type'=> 'failed']);
                    }
                } else{
                     DB::commit();
                    return response()->json(['type'=> 'success']);
                }
            } else{
                return response()->json(['type'=> 'no_domain']);
            }
        }
        catch(Exception $exception) {
            \Log::error($exception);
            DB::rollback();
            return response()->json(['type'=> 'failed']);
            
        }   
        

    }

}
