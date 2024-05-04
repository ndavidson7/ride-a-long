<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class RouteService
{
    const MIN_LOCATIONS = 4;
    const MAX_LOCATIONS = 10; // could change to 20 if paid for

    public function optimizeRoute($route, $pickup = null, $dropoff = null)
    {
        self::throwIfNotInRange(count($route) + isset($pickup) + isset($dropoff));

        // if no pickup or dropoff, just optimize the route
        if (!isset($pickup) && !isset($dropoff)) {
            return $this->getOptimizedWaypoints($route);
        }

        $this->formatRequest($route, $pickup, $dropoff);

        return $this->getOptimizedWaypoints($route);
    }

    private function appendWaypoints(&$route, ...$waypoints)
    {
        foreach ($waypoints as $waypoint) {
            array_splice($route, count($route) - 1, 0, [$waypoint]);
        }
    }

    private function getOptimizedWaypoints($route)
    {
        // Format route for RouteXL
        $locations = $this->formatRoute($route);

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
        if ($response->status() === 204 || $response['count'] !== count($locations) || $response['feasible'] != true) {
            throw new Exception('Unable to find optimal route.');
        }

        // Remove origin and destination
        array_shift($route);
        array_pop($route);

        $optimizedRoute = $response['route'];
        array_shift($optimizedRoute);
        array_pop($optimizedRoute);

        // returns the original waypoints in the correct order (also with updated order property)
        $optimizedWaypoints = array_map(function ($key) use ($route, $optimizedRoute) {
            // find the original waypoint object using the optimized route entry's name (address)
            $waypoint = $route[array_search($optimizedRoute[$key]['name'], array_column(array_column($route, 'address'), 'formatted_address'))];
            // add its order according to the optimized route's key (want order to be 1-indexed)
            $waypoint['order'] = $key + 1;
            return $waypoint;
        }, array_keys($optimizedRoute));

        return $optimizedWaypoints;
    }

    private function formatRoute($route)
    {
        return array_map(function ($key) use ($route) {
            $location = $route[$key];

            // Origin and destination have different structure
            if ($key === 0 || $key === count($route) - 1) {
                return [
                    'address' => $location['formatted_address'],
                    'lat' => $location['latitude'],
                    'lng' => $location['longitude'],
                ];
            }

            $address = $location['address'];
            $tmp = [
                'address' => $address['formatted_address'],
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
    }

    /**
     * Used for previewing and showing ride requests with pickup and/or dropoff locations,
     * as the pickup and dropoff locations are not yet waypoints. Formats the pickup and
     * dropoff locations to emulate waypoints by setting the before and/or after keys,
     * and appends them to the route.
     * 
     * Should never be called without either pickup or dropoff being set.
     */
    private function formatRequest(&$route, $pickup, $dropoff)
    {
        // Set before and/or after if necessary and append to route
        if ($pickup xor $dropoff) {
            // simply append the waypoint
            $this->appendWaypoints($route, $pickup ?? $dropoff);
        } else {
            // both pickup and dropoff are set
            if (isset($pickup["before"]) && isset($dropoff["after"])) {
                throw new Exception('Unable to optimize this pickup and dropoff combination. Try slightly different or more precise addresses.');
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

                $this->appendWaypoints($route, $pickup, $dropoff);
            }
        }
    }

    private static function throwIfNotInRange($num)
    {
        if ($num < self::MIN_LOCATIONS) {
            throw new Exception('Not enough addresses. Refresh the page and try again. If the problem persists, try different addresses.');
        } else if ($num > self::MAX_LOCATIONS) {
            throw new Exception('Too many addresses in route. Maximum, including origin and destination, is ' . self::MAX_LOCATIONS . '.');
        }
    }
}
