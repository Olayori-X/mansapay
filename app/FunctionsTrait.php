<?php

namespace App;

use Illuminate\Support\Facades\Http;

trait FunctionsTrait
{
    public function sendPostRequest($url, $data){
        try{
            $api = base64_encode("PZ78-PKTEST-748D46CE-9ADA-4F0D-95E9-F4C4452FD279");
            $response = Http::withHeaders([
                "Authorization" => "Payaza {$api}",
                "Content-Type" => "application/json",
            ])->post($url, $data);
            return $response;
        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function sendGetRequest($url){
        try{
            $api = base64_encode("PZ78-PKTEST-748D46CE-9ADA-4F0D-95E9-F4C4452FD279");
            $response = Http::withHeaders([
                "Authorization" => "Payaza {$api}",
                "Content-Type" => "application/json",
                'X-tenantID' => 'test'
            ])->get($url);
            return $response;
        }catch(\Throwable $th){
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
