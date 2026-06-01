<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function states(Request $request): JsonResponse
    {
        $country = trim((string) $request->query('country', ''));

        if (strcasecmp($country, 'Nigeria') !== 0) {
            return response()->json([]);
        }

        $locations = config('nigeria_locations', []);

        return response()->json(array_values(array_keys($locations)));
    }

    public function lgas(Request $request): JsonResponse
    {
        $country = trim((string) $request->query('country', 'Nigeria'));
        $state = trim((string) $request->query('state', ''));

        if (strcasecmp($country, 'Nigeria') !== 0 || $state === '') {
            return response()->json([]);
        }

        $locations = config('nigeria_locations', []);

        return response()->json(array_values($locations[$state] ?? []));
    }
}
