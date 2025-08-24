<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MarkNoService;

class MarkNoController extends Controller
{
    protected $markNoService;

    public function __construct(MarkNoService $markNoService)
    {
        $this->markNoService = $markNoService;
    }

    /**
     * Display a listing of mark numbers
     */
    public function index(Request $request)
    {

        $markNos = $this->markNoService->getActive();

        return response()->json([
            'success' => true,
            'data' => $markNos
        ]);
    }
}
