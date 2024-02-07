<?php

namespace App\Http\Helpers;

use App\Models\TransactionHistory;
use Stripe;
use Auth;
use DB;
use App\Models\CreditPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayementNotification;
use App\Notifications\CreditNotification;

class Helper {

    public static function currentTime() {
        return time();
    }

    public static function countEmail($arr) {
        $count = 0;
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $val) {
                if (is_array($val)) {
                    $count = $count + count($val);
                } else {
                    $count++;
                }
            }
        }
        return $count;
    }

    public static function countEmails($arr) {
        $eml = '';
        $count = 0;
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $key => $value) {
                        $eml .= $value . ',';
                    }
                } else {
                    $eml .= $val . ',';
                }
            }
        }
        $string = trim($eml, ',');
        $emails = explode(",", $string);
        $count = count($emails);

        return $count;
    }

    public static function getEmail($arr) {
        $eml = '';
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $key => $value) {
                        $eml .= $value . ',';
                    }
                } else {
                    $eml .= $val . ',';
                }
            }
        }
        $string = trim($eml, ',');
        $emails = explode(",", $string);
        foreach ($emails as $email) {
            echo "<h6>$email</h6>";
        }
        // return $emails;
    }

    public static function getAllEmail($arr) {
        $eml = '';
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $key => $value) {
                        $eml .= $value . ',';
                    }
                } else {
                    $eml .= $val . ',';
                }
            }
        }
        $string = trim($eml, ',');
        $emails = explode(",", $string);
        /* $emailArr = [];
          foreach ($emails as $email){
          $emailArr[] = $email;
          } */
        return $emails;
    }

    public static function getAllLevel($arr) {
        $eml = '';
        $level = [];
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $key => $value) {
                        $eml .= $value . ',';
                        $level[$key] = $key + 1;
                    }
                } else {
                    $eml .= $val . ',';
                    $level[0] = $key + 1;
                }
            }
        }
        $string = trim($eml, ',');
        $emails = explode(",", $string);
        $emailArr = [];
        foreach ($emails as $email) {
            $emailArr[] = $email;
        }
        return $emailArr;
    }

    public static function split_name($name) {
        $name = trim($name);
        $lastname = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $firstname = trim(preg_replace('#' . preg_quote($lastname, '#') . '#', '', $name));
        return $firstname;
    }

    public static function credits_used($credit, $plan) {
        $get = ($credit * 100) / $plan;
        $used = 100 - $get;
        return number_format($used, 2);
    }

    public static function transactionHistory($user_id, $bal_type, $payment_for, $price, $credits, $comment, $status) {
        $user = User::where('id', $user_id)->first();
        if ($bal_type == '1') {
            $old_credits = $user->credits + $credits;
        } else {
            $old_credits = $user->credits - $credits;
        }

        $addHistory = new TransactionHistory;
        $addHistory->user_id = $user_id;
        $addHistory->bal_type = $bal_type;
        $addHistory->payment_for = $payment_for;
        $addHistory->price = (!empty($price)) ? $price : 0;
        $addHistory->credits = $credits;
        $addHistory->old_credits = $old_credits;
        $addHistory->comment = $comment;
        $addHistory->status = $status;
        $addHistory->save();

        if (env('IS_NOTIFY_ENABLE', false) == true) {

            Notification::send($user, new CreditNotification($addHistory));
        }
    }

    public static function paymentIntent() {
        $user = Auth::user();
        $intent = $user->createSetupIntent(['payment_method_types' => ['card'],]);
        return $intent->client_secret;
    }

    public static function CreditPlan() {
        $creditsPlan = CreditPlan::where('status', '1')->get();
        return is_object($creditsPlan) ? $creditsPlan : null;
    }

    public static function countries() {
        $countries = DB::table('user_country')->get();
        return is_object($countries) ? $countries : null;
    }

    /* ---- create custom csv-------------------- */

    public static function forReference() {
        $batch_no = 'batch_no';
        $route = 'route';
        $send_sms_primary_route = 'send_sms_primary_route';
        $mobile = 'mobile';
        $message = 'message';
        $use_credit = 'use_credit';
        $is_auto = 'is_auto';

        $header = [$batch_no, $route, $send_sms_primary_route, $mobile, $message, $use_credit, $is_auto];

        $data = [$header, ['bacthNIum1', '1', '1', '9713753131', 'testing message', '1', 'No'], ['bacthNIum2', '1', '1', '9713753130', 'testing message', '1', 'No']];

        $fileName = null;
        $textTotalContact = 0;
        $csvData = null;
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                $csvData .= $value[0] . "," . $value[1] . "," . $value[2] . "," . $value[3] . "," . $value[4] . "," . $value[5] . "," . $value[6] . "\n";
            }
            $destinationPath = 'csv_file/';
            $fileName = "ashok-test.csv";
            $fileDestination = fopen($destinationPath . $fileName, "w");
            fputs($fileDestination, $csvData);
            fclose($fileDestination);
        }
        $returnData = [
            'fileName' => $fileName,
            'textTotalContact' => $textTotalContact,
        ];
        return $returnData;
    }

}
