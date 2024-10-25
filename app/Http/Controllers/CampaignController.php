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
                'userid' => ['required', 'min:3'],
                'title' => 'required',
                'description' => 'required',
                'price'=> 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $user = User::where('userid', $request->userid)->first();

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
            $campaign->userid = $request->userid;
            $campaign->formid = $formid;
            $campaign->title = $request->title;
            $campaign->description = $request->description;
            $campaign->price = $request->price;
            

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
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'userid' => ['required', 'min:3'],
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $campaign = Campaign::where('userid', $request->userid)->get();


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

    public function getPaymentMade(Request $request){
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

            $campaign = Payment::where('userid', $request->userid)
                                ->where('formid', $request->formid)
                                ->get();


                if($campaign){
                    return response()->json([
                        "response"=> true,
                        "paymentmade" => $campaign
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
                'userid' => ['required', 'min:3'],
                'formid' =>['required'],
                'title' => 'required',
                'description' => 'required',
                'price'=> 'required',
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


            $campaign->title = $request->title;
            $campaign->description = $request->description;
            $campaign->price = $request->price;
            

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
                'userid' => ['required', 'min:3'],
                'formid' =>['required'],
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $result = Campaign::where('formid', $request->formid)
                              ->where('userid', $request->userid)
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
