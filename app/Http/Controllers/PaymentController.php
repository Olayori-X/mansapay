<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Funds;
use App\FunctionsTrait;
use App\Models\Payment;
use App\Models\Campaign;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    use FunctionsTrait;
    public function getCampaignById(Request $request){
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

            $campaign = Campaign::where('formid', $request->formid)->first();


                if($campaign){
                    $today = strtotime("now");
                    if($today > strtotime($campaign->duedate)){
                        $result = Campaign::where('formid', $request->formid)->delete();

                        return response()->json([
                            "response"=>false,
                            "message"=> "This campaign is no longer available"
                        ]);
                    }else{
                        return response()->json([
                            "response"=> true,
                            "campaigns" => $campaign
                        ]);
                    }
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

    public function initiateBankTransfer(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'formid' =>['required'],
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'price' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $campaign = Campaign::where('formid', $request->formid)->first();

            if($campaign == null){
                return response()->json([
                    "response" => false,
                    "message" => "Campaign does not exist"
                ], 404);
            }

            $values = [15, 16, 17, 18, 19, 20];
            $reference = Str::random(Arr::random($values));

            $payload = [
                "service_type" => "Account",
                "service_payload" => [
                    "request_application" => "Payaza",
                    "application_module" => "USER_MODULE",
                    "application_version" => "1.0.0",
                    "request_class" => "MerchantCreateVirtualAccount",
                    "customer_first_name" => $request->firstname,
                    "customer_last_name" => $request->lastname,
                    "customer_email" => $request->email,
                    "customer_phone" => $request->phone,
                    "virtual_account_provider" => "Premiumtrust",
                    "payment_amount" => $request->price,
                    "payment_reference" => $reference,
                ]
            ];
            
            $response = $this->sendPostRequest('https://router.prod.payaza.africa/api/request/secure/payloadhandler' ,$payload);
            $response = $response->json();
            

            if($response['response_code'] == 200){
                $payment = new Payment();


                $payment->formid = $request->formid;
                $payment->userid = $campaign->userid;
                $payment->payer_name = $request->firstname. " ". $request->lastname;
                $payment->payer_email = $request->email;
                $payment->reference = $reference;
                $payment->paid = false;
                

                $result = $payment->save();
                
                if($result){
                    return response()->json([
                        "response"=> true,
                        "data" => $response
                    ]);
                }else{
                    return response()->json([
                        "response"=> false
                    ]);
                }
            }


        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function confirmBankTransfer(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'reference' =>['required'],
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            
            $response = $this->sendGetRequest("https://api.payaza.africa/live/merchant-collection/transfer_notification_controller/transaction-query?transaction_reference={$request->reference}");
            $response = $response->json();
            
            

            if($response['data']['transaction_status'] == "Completed"){
                $payment = Payment::where('reference', $request->reference)->first();

                if($payment->paid == false){
                    $payment->paid = true;

                    $result = $payment->save();
                
                    if($result){
                        $campaign = Campaign::where('formid', $payment->formid)->first();
                        $funds = new Funds();

                        $funds->userid = $payment->userid;
                        $funds->beneficiary = $campaign->title;
                        $funds->campaignid = $campaign->formid;
                        $funds->amount = $response['data']['amount_received'];
                        $funds->reference = $request->reference;
                        $funds->status = "Success";
                        $funds->addition = true;
                        
                        
                        $funds->save();
                        $user = User::where('userid', $payment->userid)->first();
                        $user->acc_bal += $response['data']['amount_received'];
                        $user->save();
                        return response()->json([
                            "response"=> true,
                            "data" => $response,
                            "message" => $response['data']['status_reason']
                        ]);
                    }else{
                        return response()->json([
                            "response"=> false
                        ]);
                    }
                }else{
                    return response()->json([
                        "response"=> true,
                        "data" => $response,
                        "message" => $response['data']['status_reason']
                    ]);    
                }
            
            }else{
                return response()->json([
                    "response"=> false,
                    "data" => $response,
                    "message" => $response['data']['status_reason']
                ]);
            }


        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function cardPayment(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'formid' =>['required'],
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'price' => 'required',
                "expiryMonth"=> "required",
                "expiryYear"=> "required",
                "securityCode" => "required",
                "cardNumber" => "required"
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $campaign = Campaign::where('formid', $request->formid)->first();

            if($campaign == null){
                return response()->json([
                    "response" => false,
                    "message" => "Campaign does not exist"
                ], 404);
            }

            $values = [15, 16, 17, 18, 19, 20];
            $reference = Str::random(Arr::random($values));

            
            $payload = [
                "service_type" => "Account",
                "service_payload" => [
                    "first_name" => $request->firstname,
                    "last_name" => $request->lastname,
                    "email_address" => $request->email,
                    "phone_number" => $request->phone,
                    "amount" => $request->price,
                    "transaction_reference" => $reference,
                    "currency" => "NGN",
                    "description" => "hello",
                    "card" => [
                        "expiryMonth" => $request->expiryMonth,
                        "expiryYear" => $request->expiryYear,
                        "securityCode" => $request->securityCode,
                        "cardNumber" => $request->cardNumber
                    ],
                ]
            ];
            
            $response = $this->sendPostRequest('https://cards-live.78financials.com/card_charge/', $payload);
            $response = $response->json();
            

            if($response['statusOk'] == true){
                $payment = new Payment();


                $payment->formid = $request->formid;
                $payment->userid = $campaign->userid;
                $payment->payer_name = $request->firstname. " ". $request->lastname;
                $payment->payer_email = $request->email;
                $payment->reference = $response['transactionReference'];
                $payment->paid = true;
                

                $result = $payment->save();
                
                if($result){
                    $funds = new Funds();

                        $funds->userid = $campaign->userid;
                        $funds->beneficiary = $campaign->title;
                        $funds->campaignid = $campaign->formid;
                        $funds->amount = $response['amountPaid'];
                        $funds->reference = $reference;
                        $funds->status = "Success";
                        $funds->addition = true;
                        
                        $funds->save();
                    $user = User::where('userid', $payment->userid)->first();
                        $user->acc_bal += $response['amountPaid'];
                        $user->save();
                        return response()->json([
                            "response"=> true,
                            "data" => $response,
                            "message" => $response['debugMessage']
                        ]);
                }else{
                    return response()->json([
                        "response"=> false
                    ]);
                }
            }else{
                return response()->json([
                    "response"=> false,
                    "message" => "Something went wrong",
                    "data" => $response
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
