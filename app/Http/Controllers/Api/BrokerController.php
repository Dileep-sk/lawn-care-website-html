<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    public function index()
    {
        $broker = Broker::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $broker
        ], 200);
    }
}
