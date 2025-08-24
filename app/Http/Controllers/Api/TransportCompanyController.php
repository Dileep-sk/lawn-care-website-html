<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransportCompany;
use Illuminate\Http\Request;

class TransportCompanyController extends Controller
{
     public function index()
    {
        $TransportCompany = TransportCompany::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $TransportCompany
        ], 200);
    }
}
