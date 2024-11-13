<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\Campaign;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class CampaignController extends Controller
{
    public function addCampaign(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'title' => 'required',
                'description' => 'required',
                'price'=> 'required',
                'duedate' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $user = User::where('userid', auth()->user()->userid)->first();

            if(!$user){
                return response()->json([
                    "response" => false,
                    'message' => "Unauthorized"
                ], 401);
            }else{
                if($user->email_verified_at == null){
                    return response()->json([
                        "response" => false,
                        'message' => "Email is not yet verified"
                    ], 401);
                }
            }

            $campaign = new Campaign();

            $values = [15, 16, 17, 18, 19, 20];

            $formid = Str::random(Arr::random($values));
            $campaign->userid = auth()->user()->userid;
            $campaign->formid = $formid;
            $campaign->title = $request->title;
            $campaign->description = $request->description;
            $campaign->price = $request->price;
            $campaign->duedate = $request->duedate;
            

            $result = $campaign->save();
                if($result){
                    return response()->json([
                        "response"=> true,
                        "formid"=> $formid,
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

    public function getCampaigns(Request $request){
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'userid' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $userid = auth()->user()->userid;

            if(!($request->userid == auth()->user()->userid)){
                return response()->json([
                    'response' => false,
                    'message' => "Wrong token",
                ]);
            }
            $campaigns = Campaign::where('userid', $userid)->get();
            $count = $campaigns->count();
    
            return response()->json([
                "response" => true,
                "campaigns" => $campaigns,
                "count" => $count
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    

    public function getCampaignStatus(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'formid' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $payment = Payment::where('userid', auth()->user()->userid)
                                ->where('formid', $request->formid)
                                ->get();
                                
            $campaign = Campaign::where('userid', auth()->user()->userid)
                                ->where('formid', $request->formid)
                                ->first();
                                


                if($payment){
                    return response()->json([
                        "response"=> true,
                        "paymentmade" => $payment,
                        "campaign" => $campaign
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

    public function editCampaign(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'formid' =>['required'],
                'title' => 'required',
                'description' => 'required',
                'price'=> 'required',
                'duetime'=> 'required',
                'duedate'=>'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $campaign = Campaign::where('formid', $request->formid)
                                ->where('userid', auth()->user()->userid)
                                ->first();


            $campaign->title = $request->title;
            $campaign->description = $request->description;
            $campaign->price = $request->price;
            $campaign->duedate = $request->duedate;
            $campaign->duetime = $request->duetime;
            

            $result = $campaign->save();
                if($result){
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

    public function deleteCampaign(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'userid' => 'required',
                'formid' =>['required'],
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }


            if(!($request->userid == auth()->user()->userid)){
                return response()->json([
                    'response' => false,
                    'message' => "Wrong token",
                ]);
            }

            $result = Campaign::where('formid', $request->formid)
                              ->where('userid', auth()->user()->userid)
                              ->delete();
            

                if($result){
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
