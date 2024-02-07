<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Collection;
use App\Models\DomainCollection;
use App\Models\SubscriptionPlan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UnsubscribedList;
class ListController extends Controller
{
    public function index(Request $request){
        $collections = DB::table('collections')->select(array('collections.*', DB::raw("(SELECT count(*) from campaigns WHERE campaigns.user_id = " .auth()->user()->id. " and  campaigns.list_id = collections.id ) campHistoryCount")))
                    ->where('user_id', $request->user()->id)
                    ->where('status', '=' ,1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        $collections->withPath(url()->full());
        // $favourites = DB::table('favourites')
        //             ->where('user_id', $request->user()->id)
        //             ->count();
        // dd($favourites);
        
        $plan = SubscriptionPlan::where('id',auth()->user()->plan)->first();
        return view('lists', ['collections'=>$collections,'plan'=> $plan]);
    }

    public function createList(Request $request){
        $checkAlready = Collection::where('user_id',$request->user()->id)->where('name',ucfirst($request->name))->first();
        if(is_object($checkAlready)){
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }

        $collection = new Collection;
        $collection->user_id = $request->user()->id;
        $collection->name = $request->name;
        $collection->status = 1;
        $saved = $collection->save();
        if($saved){
            $data = [
                'type'      => 'success',
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                'type'      => 'failed',
            ];
            return response()->json($data, 200);
        }
    }

    public function editList(Request $request){
        $checkAlready = Collection::where('id','!=',$request->id)->where('user_id',$request->user()->id)->where('name',ucfirst($request->name))->first();
        if(is_object($checkAlready)){
           $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }
        $collection = Collection::find($request->id);
        $collection->name = $request->name;
        $collection->save();
        if($collection){
            $data = [
                'type'      => 'success',
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                'type'      => 'failed',
            ];
            return response()->json($data, 200);
        }
    }

    public function addtoList(Request $request){
        $list = DB::table('collections')
                ->select('website_count','name','id')
                ->where('user_id', $request->user()->id)
                ->where('id', $request->collection_id)
                ->first();
        $count = $list->website_count;
       
        $plan = SubscriptionPlan::where('id',auth()->user()->plan)->first();
        if(empty($plan)){
            return response()->json(['type'=> 'error','message'=>'You can not upload a file! you have no purchase plan']);
        }
        if($count< $plan->size_limit){
            $domain = new DomainCollection;
            $domain->user_id = $request->user()->id;
            $domain->collection_id = $request->collection_id;
            $domain->domain_id = $request->domain_id;
            $domain->domain = $request->website;
            $saved = $domain->save();
            if($saved){
                $size = DB::table("domain_collections")
                        ->select()
                        ->where('user_id', $request->user()->id)
                        ->where('collection_id', '=' ,$request->collection_id)
                        ->count();
                $collections = DB::table('collections')
                            ->where('id', $request->collection_id)
                            ->update(['website_count' => $size]);
                return response()->json(['type'=> 'success','name'=> $list->name,'message'=>'Added to List Successfully']);
            }else{
                return response()->json(['type'=> 'error','message'=>'Oops.! Something went wrong']);
            }
        }else{
           return response()->json(['type'=> 'error','message'=>'Limit Exceeded Max '.$plan->size_limit.' domain']);
        }
    }

    public function removetoList(Request $request){
        $domain = DB::table('domain_collections')
                ->where('collection_id', $request->collection_id)
                ->where('domain_id', $request->domain_id)
                ->delete();
        if($domain){
            $size = DB::table("domain_collections")
                    ->select()
                    ->where('user_id', $request->user()->id)
                    ->where('collection_id', '=' ,$request->collection_id)
                    ->count();
            $collections = DB::table('collections')
                        ->where('id', $request->collection_id)
                        ->first();
            $collectionsupd =Collection::find($request->collection_id);
            $collectionsupd->website_count = $size;
            $collectionsupd->save();
                      
            return response()->json(['type'=> 'success','name'=> $collections->name,'message'=>'Remove to List Successfully']);
        }else{
           return response()->json(['type'=> 'error','message'=>'Oops.! Something went wrong']);
        }
    }

    public function deleteList(Request $request){
        $collection = DB::table('collections')
                    ->where('id', '=', $request->id)
                    ->where('user_id', $request->user()->id)
                    ->update(['status' => 2]);
        if($collection){
            return '1';
        }else{
            return '2';
        }
    }

    public function downloadUnsubscribedList(Request $request){
        return Excel::download(new UnsubscribedList, 'UnsubscribeList.csv');
    }

}
