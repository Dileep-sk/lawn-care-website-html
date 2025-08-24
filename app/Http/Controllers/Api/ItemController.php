<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ItemService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(Request $request)
    {
        $onlyActive = $request->boolean('active', false);
        $items = $this->itemService->getAll($onlyActive);

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
}
