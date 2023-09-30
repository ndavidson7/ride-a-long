<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RouteService;

class RoutePlannerController extends Controller
{
    public function optimize(Request $request, RouteService $routeService)
    {
        $data = json_decode($request->getContent(), true);

        try {
            $optimizedWaypoints = $routeService->optimizeRoute($data['route'], $data['pickup'] ?? null, $data['dropoff'] ?? null);
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
