<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh', 'logout']]);
    }


    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'lastName', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'lastName' => 'sometimes|nullable|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Utente creato con successo',
            'data' => $user
        ], 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        Log::info("[AuthApiController:login] Incoming request" );

        try {
            $this->validate($request, [
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $credentials = $request->only(['email', 'password']);
    
            if (! $token = Auth::attempt($credentials)) {
                Log::info("[AuthApiController:login] Utente non autorizzato " );
                return response()->json(['message' => 'Utente non autorizzato'], 401);
            }
    
            Log::info("[AuthApiController:login] token: ".$token );
    
    
            return $this->respondWithToken($token);
    
        } catch (Throwable $e) {
            Log::error("Error ".$e);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
