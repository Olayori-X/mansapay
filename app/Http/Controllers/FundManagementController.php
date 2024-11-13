<?php

namespace App\Http\Controllers;

use auth;
use App\Models\User;
use App\Models\Funds;
use App\FunctionsTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FundManagementController extends Controller
{
    use FunctionsTrait;
    public function withdrawFunds(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                "amount" => "required",
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            if(auth()->user()->acc_bal < $request->amount){
                return response()->json([
                    'response' => false,
                    'message' => "Your account balance is low"
                ]);
            }


            $values = [15, 16, 17, 18, 19, 20];
            $reference = random_int(100000, 999999999999999999);

            
            $payload = [
                "transaction_type" => "nuban",
                "service_payload" => [
                    "payout_amount"=> 100,
                    "transaction_pin" => 1111,
                    "account_reference" => $reference,
                    "currency" => "NGN",
                    "country" => "NGA",
                    "payout_beneficiaries" => [
                        [
                            "credit_amount" => $request->amount,
                            "account_number"=> auth()->user()->acc_number,
                            "account_name"=> auth()->user()->acc_name,
                            "bank_code"=> auth()->user()->bank_code,
                            "narration"=> "Test",
                            "transaction_reference"=> $reference,
                            "sender"=> [
                                "sender_name"=> "MansaPay",
                                "sender_id"=> "",
                                "sender_phone_number"=> "09030816273",
                                "sender_address"=> ""
                            ]
                        ]
                    ]
                ]
            ];
            
            $response = $this->sendPostRequest('https://api.payaza.africa/live/payout-receptor/payout', $payload);
            $response = $response->json();
            echo "response: ";
            print_r($payload);
            

            if($response['response_code'] == 200){
                $response = $this->sendGetRequest("https://api.payaza.africa/live/payaza-account/api/v1/mainaccounts/merchant/transaction/{$request->reference}");
                $response = $response->json();
                $funds = new Funds();


                $funds->userid = auth()->user()->userid;
                $funds->beneficiary = auth()->user()->acc_name;
                $funds->campaignid = null;
                $funds->amount = $response['transaction_amount'] + $response['fee'] ?? $request->amount + 10;
                $funds->reference = $reference;
                $funds->status = $response['transactionStatus'] ?? "Pending";
                

                $result = $funds->save();
                
                if($result){
                    $user = User::where('userid', auth()->user()->userid)->first();
                        $user->acc_bal -= $response['transaction_amount'] + $response['fee'] ?? $request->amount + 10;
                        $user->save();
                        return response()->json([
                            "response"=> true,
                            "data" => $response,
                            "message" => $response['responseMessage']
                        ]);
                }else{
                    return response()->json([
                        "response"=> false
                    ]);
                }
            }else{
                return response()->json([
                    "response"=> $response
                ]);
            }


        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getTransactionHistory(Request $request){
        $validateUser = Validator::make($request->all(), 
            [
                'userid' => 'required'
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
        $transaction_report = Funds::where('userid', auth()->user()->userid)->get();

        return response()->json([
            "response" => true,
            "data" => $transaction_report
        ]);
    }
}
