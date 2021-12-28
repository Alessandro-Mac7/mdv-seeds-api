<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info("[CorsMiddleware:handle] Begin cors add request: ".$request );
        $headers = [
            'Access-Control-Allow-Origin'       => '*',
            'Access-Control-Allow-Methods'      => 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Credentials'  => 'true',
            'Access-Control-Max-Age'            => '86400',
            'Access-Control-Allow-Headers'      => 'Origin, Content-Type, X-Auth-Token, Accept, Authorization, X-Requested-With, X-CSRF-Token'
        ];
    
        if ($request->isMethod('OPTIONS')){
            return response()->json(['method' => 'OPTIONS'], 200, $headers);
        }

        
        $response = $next($request);
        foreach($headers as $key => $value) {
            $response->header($key, $value);
        }
        

        Log::info("[CorsMiddleware:handle] response: ".$response);

        return $response;
    }
}