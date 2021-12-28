<?php

namespace App\Http\Controllers;

use App\Models\Seed;
use App\Models\User;
use App\Models\UserSeeds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserSeedApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function showAllUserSeed(Request $request, $id)
    {
        Log::info("[UserSeedApiController:login] Incoming request" );
        if ($request->input('last-seed') && $request->input('last-seed') == true) {

            Log::info("[UserSeedApiController:login] last-seed active" );

            $userSeed = UserSeeds::where('user_id', $id)->with('seed')->orderBy('created_at','desc')->first();

            if (!$userSeed) {
                Log::info("[UserSeedApiController:login] no seed for user ".$id );
                $rows['message'] = 'Nessun semino disponibile, pescane uno';
                $rows['response'] = "warning";
                $rows['data'] = null;
        
                return response()->json($rows);
            }

            Log::info("[UserSeedApiController:login] last seed found for user: ".$id );

            $response['message'] = $userSeed->seed->message;
            $response['color'] = $userSeed->seed->color;
            $response['pickDate'] = $userSeed->pick_date;
            $rows['response'] = "success";
            $rows['data'] = $response;
    
            return response()->json($rows);
        }
        
        Log::info("[UserSeedApiController:login] seed collection active" );

        $userSeeds = UserSeeds::where('user_id', $id)->with('seed')->get();
        
        if (!$userSeeds) {
            Log::info("[UserSeedApiController:login] no seed for user ".$id );
            $rows['message'] = 'Nessun semino disponibile, pescane uno';
            $rows['response'] = "warning";
            $rows['data'] = [];
    
            return response()->json($rows);
        }

        Log::info("[UserSeedApiController:login] collection seed found for user: ".$id );

        foreach ($userSeeds as $us) {
            $response['message'] = $us->seed->message;
            $response['color'] = $us->seed->color;
            $response['pickDate'] = $us->pick_date;
            $collectionResponse[] = $response;
        }

        $rows['response'] = "success";
        $rows['data'] = $collectionResponse;
    
        return response()->json($rows);
    }

    //
    public function createNewRandomSeed($id)
    {
        $user = User::findOrFail($id);
        $user->loadCount(['userSeeds' => function ($query) {
            $query->where('created_at', '>=', date("Y-m-d"));
        }]);

        if ($user->user_seeds_count >= 10) {
            $rows['response'] = "warning";
            $response['maxPick'] = 10;
            $response['dayPick'] = $user->user_seeds_count;
            $rows['data'] = $response;
            return response()->json($rows, 200);
        }

        $us = new UserSeeds();
        $us->user_id= $id;
        $us->seed_id= Seed::inRandomOrder()->first()->id;
        $us->pick_date=date("Y-m-d");
        $us->save();

        $rows['response'] = "success";
        $response['message'] = $us->seed->message;
        $response['color'] = $us->seed->color;
        $response['pickDate'] = $us->pick_date;
        $response['maxPick'] = 10;
        $response['dayPick'] = $user->user_seeds_count;
        $rows['data'] = $response;

        return response()->json($rows, 200);
    }
}
