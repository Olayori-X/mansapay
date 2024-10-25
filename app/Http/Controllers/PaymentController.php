<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function getCampaignById(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'userid' => ['required', 'min:3'],
                'formid' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $campaign = Campaign::where('userid', $request->userid)
                                ->where('formid', $request->formid)
                                ->first();


                if($campaign){
                    return response()->json([
                        "response"=> true,
                        "campaigns" => $campaign
                    ]);
                }else{
                    return response()->json([
                        "response"=> false
                    ]);
                }

        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function makePayment(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'userid' => ['required', 'min:3'],
                'formid' =>['required'],
                'name' => 'required',
                'email' => 'required',
                'price' => 'required',
                'reference'=> 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $campaign = Campaign::where('formid', $request->formid)
                                ->where('userid', $request->userid)
                                ->first();

            if($campaign == null){
                return response()->json([
                    "response" => false,
                    "message" => "Campaign does not exist"
                ], 404);
            }

            $payment = new Payment();


            $payment->userid = $request->userid;
            $payment->formid = $request->formid;
            $payment->payer_name = $request->name;
            $payment->payer_email = $request->email;
            $payment->reference = $request->reference;
            

            $result = $payment->save();
                if($result){
                    $user = User::where('userid', $request->userid)->first();
                    $user->acc_bal += $request->price;
                    $user->save();
                    return response()->json([
                        "response"=> true
                    ]);
                }else{
                    return response()->json([
                        "response"=> false
                    ]);
                }

        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
