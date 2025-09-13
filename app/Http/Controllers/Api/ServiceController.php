<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Services\ServiceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', null);

        $stocks = $this->serviceService->getServices($perPage, $search);

        return response()->json($stocks, 200);
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $serice = $this->serviceService->create($request->validated());

            return response()->json([
                'message' => 'Service created successfully',
                'data' => $serice
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateServiceRequest $request, int $id): JsonResponse
    {
        try {
            $service = $this->serviceService->update($id, $request->validated());

            return response()->json([
                'message' => 'Service updated successfully',
                'data' => $service
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $service = $this->serviceService->getServiceById($id);

            if (!$service) {
                return response()->json(['message' => 'Service not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $service
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch service.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
