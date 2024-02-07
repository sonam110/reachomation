<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;
use App\Models\SubscriptionPlan;
use App\Models\User;
class SubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $content;
   
    public function __construct($content) {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
       

        return $this->markdown('email.subscription-mail')
             ->attachData($this->invoiceGenerate(), $this->content['fileName'])
           ->from(env('MAIL_FROM_ADDRESS','support@reachomation.com'),'Reachomation Support')
            ->subject('Your Plan has been successfully activate')
            ->with('content',$this->content);
    }

    public function invoiceGenerate()
    {
        $plan =SubscriptionPlan::where('id',$this->content['plan_id'])->first();
        $userInfo =User::where('id',$this->content['user_id'])->first();
        $data = [
            'plan' =>  $plan,
            'userInfo' =>  $userInfo,
            'invoice_id' => $this->content['invoice_id'],
            'invoice_detail' => $this->content['invoice_detail'],
        ];
        $pdf = PDF::loadView('invoice', $data);
        return $pdf->output();
    }


}
