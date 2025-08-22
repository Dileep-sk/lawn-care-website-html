<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateJobStatusRequest;
use Illuminate\Http\Request;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Exception;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $jobs = $this->jobService->getAllJobs($request);
            return response()->json([
                'success' => true,
                'data' => $jobs,
                'message' => 'Jobs retrieved successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch jobs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateStatus(UpdateJobStatusRequest $request, $id): JsonResponse
    {
        try {
            $updated = $this->jobService->updateJobStatus($id, $request->status);

            if ($updated) {
                return response()->json(['message' => 'Job status updated successfully.']);
            }

            return response()->json(['message' => 'Job not found or status not updated.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update Job status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $result = $this->jobService->deleteJobById($id);

            if ($result) {
                return response()->json(['message' => 'Job deleted successfully.']);
            }
            return response()->json(['message' => 'Job not found.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function show($id): JsonResponse
    {
        $job = $this->jobService->getJobById($id);
        return response()->json($job);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_name' => 'required|string',
            'design_no' => 'required|string',
            'image' => 'nullable|string',
            'quantity' => 'required|integer',
            'order_no' => 'required|string',
            'status' => 'required|integer',
            'message' => 'required|string',
            'matching_1' => 'nullable|string',
            'matching_2' => 'nullable|string',
            'matching_3' => 'nullable|string',
            'matching_4' => 'nullable|string',
            'matching_5' => 'nullable|string',
            'matching_6' => 'nullable|string',
            'matching_7' => 'nullable|string',
            'matching_8' => 'nullable|string',
        ]);

        $job = $this->jobService->createJob($data);
        return response()->json($job, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'company_name' => 'sometimes|required|string',
            'design_no' => 'sometimes|required|string',
            'image' => 'nullable|string',
            'quantity' => 'sometimes|required|integer',
            'order_no' => 'sometimes|required|string',
            'status' => 'sometimes|required|integer',
            'message' => 'sometimes|required|string',
            'matching_1' => 'nullable|string',
            'matching_2' => 'nullable|string',
            'matching_3' => 'nullable|string',
            'matching_4' => 'nullable|string',
            'matching_5' => 'nullable|string',
            'matching_6' => 'nullable|string',
            'matching_7' => 'nullable|string',
            'matching_8' => 'nullable|string',
        ]);

        $job = $this->jobService->updateJob($id, $data);
        return response()->json($job);
    }
}
