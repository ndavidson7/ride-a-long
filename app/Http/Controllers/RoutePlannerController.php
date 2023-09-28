<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class RoutePlannerController extends Controller
{
    public function optimize(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $route = $data['route'];
        $pickup = $data['pickup'];
        $dropoff = $data['dropoff'];

        $num = count($route) + isset($pickup) + isset($dropoff);
        if ($num < 4) {
            return response()->json([
                'error' => true,
                'content' => 'Not enough addresses. Refresh the page and try again. If the problem persists, try different addresses.',
            ]);
        } else if ($num > 10) {
            return response()->json([
                'error' => true,
                'content' => 'Too many addresses in route. Maximum, including origin and destination, is 10.',
            ]);
        }

        // Set before and/or after if necessary and append to route
        if ($pickup xor $dropoff) {
            // simply append the waypoint
            $this->appendWaypoints($route, $pickup ?? $dropoff);
        } else {
            // both pickup and dropoff are set
            if (isset($pickup["before"]) && isset($dropoff["after"])) {
                return response()->json([
                    'error' => true,
                    'content' => 'Unable to optimize this pickup and dropoff combination. Try slightly different or more precise addresses.',
                ]);
            }

            if (isset($pickup["before"])) {
                // pickup is already in the route, before is set, and dropoff.after is null
                $dropoff["after"] = $pickup["id"];

                // if dropoff is not already in the route, push it (otherwise, setting after is enough)
                // dropoff would have before if already in route (could be null, don't care)
                if (!array_key_exists("before", $dropoff)) {
                    $this->appendWaypoints($route, $dropoff);
                }
            } else if (isset($dropoff["after"])) {
                $pickup["before"] = $dropoff["id"];

                if (!array_key_exists("after", $pickup)) {
                    $this->appendWaypoints($route, $pickup);
                }
            } else {
                // Neither pickup nor dropoff are already in the route
                $pickup["before"] = $dropoff["id"];
                $dropoff["after"] = $pickup["id"];

                $this->appendWaypoints($route, [$pickup, $dropoff]);
            }
        }

        // Format locations for RouteXL
        $locations = array_map(function ($key) use ($route) {
            $location = $route[$key];

            // Origin and destination have different structure
            if ($key === 0 || $key === count($route) - 1) {
                return [
                    'address' => $location['address'],
                    'lat' => $location['latitude'],
                    'lng' => $location['longitude'],
                ];
            }

            $address = $location['address'];
            $tmp = [
                'address' => $address['address'],
                'lat' => $address['latitude'],
                'lng' => $address['longitude'],
            ];

            // Specify if location is pickup or dropoff
            if (isset($route[$key]['before']) || isset($route[$key]['after'])) {
                $tmp['restrictions'] = [];

                $ids = array_column($route, 'id');
                if (isset($route[$key]['before'])) {
                    $tmp['restrictions']['before'] = array_search($route[$key]['before'], $ids);
                }

                if (isset($route[$key]['after'])) {
                    $tmp['restrictions']['after'] = array_search($route[$key]['after'], $ids);
                }
            }

            return $tmp;
        }, array_keys($route));

        // Send request to RouteXL
        $response = Http::asForm()->withHeaders(['Accept-Encoding' => 'gzip, deflate'])->acceptJson()
            ->withBasicAuth(config('routexl.username'), config('routexl.password'))
            ->retry(7, 100, function (Exception $exception) {
                // 429: Another route in progress
                if (!$exception instanceof RequestException || $exception->response->status() !== 429) {
                    return false;
                }

                return true;
            })->post("https://api.routexl.com/tour", [
                'locations' => json_encode($locations),
            ]);

        // Check for errors
        if ($response->failed()) {
            Log::error('RouteXL API error: ' . $response->body());
        }

        $response->throw();

        // Check if count is correct and if feasible
        if ($response->status() == "204" || $response['count'] !== count($locations) || $response['feasible'] != true) {
            return response()->json([
                'error' => true,
                'content' => 'Unable to find optimal route.',
            ]);
        }


        // Remove origin and destination
        array_shift($route);
        array_pop($route);

        $waypoints = $response['route'];
        array_shift($waypoints);
        array_pop($waypoints);

        $optimizedWaypoints = array_map(function ($key) use ($route, $waypoints) {
            return $route[array_search($waypoints[$key]['name'], array_column(array_column($route, 'address'), 'address'))];
        }, array_keys($waypoints));

        return response()->json([
            'error' => false,
            'content' => $optimizedWaypoints,
        ]);
    }

    private function appendWaypoints(&$route, $waypoints)
    {
        foreach ((array)$waypoints as $waypoint) {
            array_splice($route, count($route) - 1, 0, [$waypoint]);
        }
    }
}
