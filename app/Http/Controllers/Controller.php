<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //Add this method to the Controller class
    protected function respondWithToken($token)
    {
        $headers = [
            'Access-Control-Allow-Origin'       => '*',
            'Access-Control-Allow-Methods'      => 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Credentials'  => 'true',
            'Access-Control-Max-Age'            => '86400',
            'Access-Control-Allow-Headers'      => 'Origin, Content-Type, X-Auth-Token, Accept, Authorization, X-Requested-With, X-CSRF-Token'
        ];
    
        return response()->json([
            'userId' => auth()->user()->id,
            'name' => auth()->user()->name,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200, $headers);
    }
}
