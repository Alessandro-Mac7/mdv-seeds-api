<?php

namespace App\Http\Controllers;

use App\Models\Seed;
use Illuminate\Http\Request;

class SeedApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function showAllSeed()
    {;
        $seeds = Seed::all();
        $response['response'] = "success";
        $response['data'] = $seeds;
        return response()->json($response, 200);
    }

    //
    public function create(Request $request)
    {
        $seed = Seed::create($request->all());

        $response = SeedApiController::toDto($seed);
        $response['response'] = "success";

        return response()->json($response, 200);
    }

    //
    public function update(Request $request, $id)
    {
        $seed = Seed::findOrFail($id);
        
        $noUpdate = true;

        if ($request->message && $request->message != $seed->message) {
            $noUpdate = false;
            $seed->message = $request->message;
        }

        if ($request->color && $request->color != $seed->color) {
            $noUpdate = false;
            $seed->color = $request->color;
        }

        if ($request->code && $request->code != $seed->code) {
            $noUpdate = false;
            $seed->code = $request->code;
        }

        if ($noUpdate) {
            $rows['response'] = "warning";
            $response['message'] = "Niente da aggiornare";
            $rows['data'] = $response;
            return response()->json($rows, 200);
        }

        $seed->update();

        $response = SeedApiController::toDto($seed);
        $response['response'] = "success";

        return response()->json($response, 200);
    }

    //
    public function destroy($id)
    {
        $seed = Seed::findOrFail($id);
        $seed->delete();

        $response = SeedApiController::toDto($seed);
        $response['response'] = "success";

        return response()->json($response, 200);
    }

    public static function toDto(Seed $seed) {
        $response['id'] = $seed->id;
        $response['message'] = $seed->message;
        $response['color'] = $seed->color;
        $response['code'] = $seed->code;
        $rows['data'] = $response;

        return $rows;
    }
}
