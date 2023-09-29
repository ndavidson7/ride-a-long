<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RouteService;

class RoutePlannerController extends Controller
{
    public function optimize(Request $request, RouteService $routeService)
    {
        $data = json_decode($request->getContent(), true);

        $route = $data['route'];
        $pickup = $data['pickup'];
        $dropoff = $data['dropoff'];

        try {
            $optimizedWaypoints = $routeService->optimizeRequest($route, $pickup, $dropoff);
            return response()->json([
                'error' => false,
                'content' => $optimizedWaypoints,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'content' => $th->getMessage(),
            ]);
        }
    }
}
