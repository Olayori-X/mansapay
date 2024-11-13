<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FundManagementController;

//get campaigns by id for who wants to pay
Route::post('/getcampaignbyid', [PaymentController::class, 'getCampaignById']);

//make payment
Route::post('/makepayment', [PaymentController::class, 'makePayment']);

//initiate bank transfer
Route::post('/initiatebanktransfer', [PaymentController::class, 'initiateBankTransfer']);

//confirm bank transfer
Route::post('/confirmbanktransfer', [PaymentController::class, 'confirmBankTransfer']);

//card payment
Route::post('/cardpayment', [PaymentController::class, 'cardPayment']);

//signup
Route::post('/signup', [UserController::class, 'signup']);

//verify otp
Route::post('/verifyotp', [UserController::class, 'verify']);

//login
Route::post('/login', [UserController::class, 'authenticate']);

//complete profile
Route::middleware('auth:sanctum')->post('/completeprofile', [UserController::class, 'completeProfile']);

Route::middleware('auth:sanctum')->post('/getuserprofile', [UserController::class, 'getUserProfile']);

Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//add campaign
Route::middleware('auth:sanctum')->post('/addcampaign', [CampaignController::class, 'addCampaign']);

//get campaigns user created
Route::middleware('auth:sanctum')->post('/getcampaigns', [CampaignController::class, 'getCampaigns']);

//get campaigns by id for creators of campaign
Route::middleware('auth:sanctum')->post('/getcampaignstatus', [CampaignController::class, 'getCampaignStatus']);

//edit campaign
Route::middleware('auth:sanctum')->post('/editcampaign', [CampaignController::class, 'editCampaign']);

//delete campaign
Route::middleware('auth:sanctum')->post('/deletecampaign', [CampaignController::class, 'deleteCampaign']);

//withdraw money
Route::middleware('auth:sanctum')->post('/withdraw', [FundManagementController::class, 'withdrawFunds']);

//get transaction history
Route::middleware('auth:sanctum')->post('/gettransactionhistory', [FundManagementController::class, 'getTransactionHistory']);