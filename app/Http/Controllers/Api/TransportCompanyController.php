<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TransportCompanyService;
use Illuminate\Http\JsonResponse;

class TransportCompanyController extends Controller
{
    protected $transportCompanyService;

    public function __construct(TransportCompanyService $transportCompanyService)
    {
        $this->transportCompanyService = $transportCompanyService;
    }

    /**
     * Return active transport companies.
     */
    public function index(): JsonResponse
    {
        $companies = $this->transportCompanyService->getActiveCompanies();

        return response()->json([
            'success' => true,
            'data' => $companies,
        ]);
    }
}
