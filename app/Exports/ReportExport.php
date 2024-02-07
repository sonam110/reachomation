<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\SendCampaignMail;
use App\Models\Inbox;
use App\Models\Feed;
use Auth;
class ReportExport implements FromCollection,WithHeadings
{
	use Exportable;
	
	protected $camp_id;
	protected $level;
	protected $type;
	public function __construct($camp_id,$level,$type)
	{
	    $this->camp_id = $camp_id;
	    $this->level = $level;
	    $this->type = $type;
    	return $this;
	}

	public function headings(): array {
	    return [
	      'SNO',
	      'Name',
	      'Email',
	      'Website',
	      'Delivery Time',
	      'Failed',
	      'Bounce',
	      'Open Count',
	      'Last Open Date',
	      'Replied',
	      'Replied Date',
	      'Click Count',
	      'Last Click Date',
	      'Failed Reason',
	    ];
	 }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	
    	  if($this->type =='2'){
    	  		$allChilds = getChildren($this->camp_id);
          	$allChild = array_merge($allChilds,[$this->camp_id]);
          	$reporData = \DB::table('send_campaign_mails')->whereIn('camp_id',$allChild)->get();

    	  } else{
	    	  if(is_null($this->level) == false){
	    	  		$reporData = \DB::table('send_campaign_mails')->where('camp_id',$this->camp_id)->where('level',$this->level)->get();
	    	  } else{
	    	  		$reporData = \DB::table('send_campaign_mails')->where('camp_id',$this->camp_id)->get();
	    	  }
        }
       //dd($reporData);
        return $array = $reporData->map(function ($b, $key) {
        /*	$msg = $b->message;
        	$msg = substr(strip_tags($msg),0,110);
        	$msg = str_replace(chr(194)," ",$msg);*/
          $status = 'Pending';
          if($b->status == '1'){
             $status ='Send';
          }
          if($b->status == '2'){
             $status ='Unsubscribed';
          }
          if($b->status == '3'){
             $status ='Bounc';
          }
          if($b->status == '4'){
             $status ='Invalid Email';
          }
          if($b->status == '5'){
             $status ='In Process';
          }
          
          $mail_send_date = date('l d M Y H:i:s A',strtotime($b->mail_send_date));
					return [
				      'SNO'   					=> $key+1,
				      'Name'   			=> $b->name,
				      'Email'   			=> $b->to_email,
				      'Website'   			=> $b->website,
				      'Delivery Time'   => (!empty($b->mail_send_date)) ? $mail_send_date: '',
				      'Failed'   =>    ($b->status =='2' || $b->status=='4') ? '1': '0',
				      'Bounce'   =>    ($b->status =='3') ? '1': '0',
				      'Open Count'   =>   $b->is_open,
				      'Last Open Date'   => (!empty($b->is_open_time)) ? date('l d M Y H:i:s A',strtotime($b->is_open_time)): '',
				      'Replied'   =>    ($b->is_reply =='1') ? '1': '0',
				      'Replied Date'   => (!empty($b->last_reply_time)) ? date('l d M Y H:i:s A',strtotime($b->last_reply_time)): '',
				      'Click Count'   =>   $b->is_click,
				      'Last Click Date'   =>  (!empty($b->last_click_time)) ? date('l d M Y H:i:s A',strtotime($b->last_click_time)): '',
				      'Failed Reason' => ($b->status =='2' || $b->status=='4') ? $status : '',
					];
				});
    }


    
}
