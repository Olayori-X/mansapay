<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendMail;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function signup(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'name' => ['required', 'min:3'],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => 'required|confirmed|min:6',
                'phone' => 'required|numeric|digits:11',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            
            // define("definedcode", Str::random(8), false);
            $code = random_int(100000, 999999);
            $signupmail = $request->email;

            $values = [15, 16, 17, 18, 19, 20];

            $userid = Str::random(Arr::random($values));

            $message = [
                'code' => $code,
                'name' => $request->name
            ];
            $email = new SendMail($message);
            Mail::to($signupmail)->send($email);
            
            $user = User::create([
                'userid' => $userid,
                'name' => $request->name,
                'email' => $signupmail,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'otp' => Hash::make($code),
            ]);


            return response()->json([
                'status' => true,
                'message' => 'Check your mail for verification',
                'userid' => $userid
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function verify(Request $request){
        try{
            $validateInput = Validator::make($request->all(), [
                'otp' => 'required',
                'userid' => 'required'
            ]);

            $userid = $request->userid;
            $otp = $request->otp;

            if($validateInput->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Validation errors",
                    "error" => $validateInput->errors()
                ], 401);
            }

            $user = User::where('userid', $userid)->first();

                    
            if ($user && Hash::check($otp, $user->otp)) {
                $user->email_verified_at = Carbon::now();
            
                // Save the updated user model to the database
                $user->save();
            
                // Optionally, you can return a response or perform further actions
                return response()->json(['status' => true, 'message' => 'Email verified successfully']);
            } else {
                // Handle the case where the user was not found
                return response()->json(['status' => false, 'message' => 'Invalid username or OTP', 'name' => $userid,]);
            }

        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request){
        $token = $request->user()->currentAccessToken();

        // Check if there is a token and delete it
        if ($token) {
            $token->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.'
        ], 200);
    }

    public function authenticate(Request $request) {

        try{
            $validateUser = Validator::make($request->all(), 
            [
                'email' => ['required', 'email'],
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => "Incorrect Email or Password"
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            

            if($user->email_verified_at != null){
                return response()->json([
                    'status' => true,
                    'message' => "User logged in successfully",
                    'token' => $user->createToken($user->userid)->plainTextToken,
                    "userid"=> $user->userid
                ]);
            }else{
                $code = random_int(100000, 999999);
                $signupmail = $request->email;

                $message = [
                    'code' => $code,
                    'name' => $request->name
                ];
                $email = new SendMail($message);
                Mail::to($signupmail)->send($email);


                $user->remember_token = Hash::make($code);
            
                $user->save();

                return response()->json([
                    'status' => false,
                    'username' => $user->name,
                    'message' => "Email is not verified. An otp has been sent to the email",
                ]);
            }
        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function completeProfile(Request $request){
        try{
            $validateUser = Validator::make($request->all(), 
            [
                'userid' => ['required', 'min:3'],
                'bankname' => 'required',
                'accountnumber' => 'required',
                'accountname'=> 'required',
                'bankcode' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'response' => false,
                    'message' => "Validation errors",
                    "error" => $validateUser->errors()
                ]);
            }

            $user = User::where('userid', $request->userid)->first();


            $user->bank_name = $request->bankname;
            $user->bank_code = $request->bankcode;
            $user->acc_number = $request->accountnumber;
            $user->acc_name = $request->accountname;

            $result = $user->save();
                if($result){
                    return response()->json([
                        "response" => true,
                        "message" => "Successful"
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

    public function getUserProfile(Request $request){
        try{
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

            if(!($request->userid == auth()->user()->userid)){
                return response()->json([
                    'response' => false,
                    'message' => "Wrong token",
                ]);
            }

            $user = User::where('userid', auth()->user()->userid)->first();

                if($user){
                    return response()->json([
                        "response"=> true,
                        "userdetails" => $user
                    ]);
                }else{
                    return response()->json([
                        "response"=> false,
                        "message" => "Not found"
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
