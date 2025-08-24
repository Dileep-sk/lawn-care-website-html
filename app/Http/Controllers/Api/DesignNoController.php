<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DesignNoService;
use Illuminate\Http\Request;

class DesignNoController extends Controller
{
    protected $designNoService;

    public function __construct(DesignNoService $designNoService)
    {
        $this->designNoService = $designNoService;
    }

    public function index(Request $request)
    {
        $designNos = $this->designNoService->getAll();

        return response()->json([
            'success' => true,
            'data' => $designNos
        ]);
    }
}
