<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use Illuminate\Http\Request;

class OrganisationController extends Controller
{
    public function index()
    {
        $organisation = Organisation::first();

        if (!$organisation) {
            return response()->json([
                'status'  => false,
                'message' => 'No organisation found',
            ], 404);
        }

        // Add full path to logo if it exists
        if ($organisation->logo) {
            $organisation->logo = asset('images/' . $organisation->logo);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Organisation retrieved successfully',
            'data'    => $organisation,
        ], 200);
    }
}
