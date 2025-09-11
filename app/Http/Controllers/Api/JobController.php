<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobStatusRequest;
use App\Models\Customer;
use App\Models\DesignNo;
use App\Models\Item;
use App\Models\MarkNo;
use App\Models\Order;
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

    public function store(CreateJobRequest $request)
    {
        try {
            if ($request->job_id) {
                $isCustomerNameExit = Customer::where('name', $request->customer_name)->first();
                if (!$isCustomerNameExit) {
                    return response()->json([
                        'message' => 'Customer Name does not exist. Please select a correct Customer Name.',
                        'error' => ""
                    ], 500);
                }

                $isMarkNoExit = MarkNo::where('name', $request->mark_no)->first();
                if (!$isMarkNoExit) {
                    return response()->json([
                        'message' => 'Mark No does not exist. Please select a correct Mark No.',
                        'error' => ""
                    ], 500);
                }

                $isDesignNameExit = DesignNo::where('name', $request->design_no)->first();
                if (!$isDesignNameExit) {
                    return response()->json([
                        'message' => 'Design No does not exist. Please select a correct Design No.',
                        'error' => ""
                    ], 500);
                }

                $isItemExit = Item::where('name', $request->item_name)->first();
                if (!$isItemExit) {
                    return response()->json([
                        'message' => 'Item Name does not exist. Please select a correct Item Name.',
                        'error' => ""
                    ], 500);
                }

                $isOrderNoExit = Order::where('order_no', $request->order_no)->first();
                if (!$isOrderNoExit) {
                    return response()->json([
                        'message' => 'Order No does not exist. Please select a correct Order No.',
                        'error' => ""
                    ], 500);
                }
            } else {
                $isCustomerNameExit = Customer::where('name', $request->customer_name)->first();
                if (!$isCustomerNameExit) {
                    return response()->json([
                        'message' => 'Customer Name does not exist. Please select a correct Customer Name.',
                        'error' => ""
                    ], 500);
                }

                $isMarkNoExit = MarkNo::where('name', $request->mark_no)->first();
                if (!$isMarkNoExit) {
                    return response()->json([
                        'message' => 'Mark No does not exist. Please select a correct Mark No.',
                        'error' => ""
                    ], 500);
                }

                $isDesignNameExit = DesignNo::where('name', $request->design_no)->first();
                if (!$isDesignNameExit) {
                    return response()->json([
                        'message' => 'Design No does not exist. Please select a correct Design No.',
                        'error' => ""
                    ], 500);
                }

                $isItemExit = Item::where('name', $request->item_name)->first();
                if (!$isItemExit) {
                    return response()->json([
                        'message' => 'Item Name does not exist. Please select a correct Item Name.',
                        'error' => ""
                    ], 500);
                }

                $isOrderNoExit = Order::where('name', $request->order_no)->first();
                if (!$isOrderNoExit) {
                    return response()->json([
                        'message' => 'Order No does not exist. Please select a correct Order No.',
                        'error' => ""
                    ], 500);
                }
            }

            $validatedData = $request->validated();
            unset($validatedData['customer_name']);
            unset($validatedData['mark_no']);
            unset($validatedData['design_no']);
            unset($validatedData['item_name']);
            unset($validatedData['order_no']);

            $jobData = array_merge($validatedData, [
                'customer_id' => $isCustomerNameExit->id,
                'mark_no_id' => $isMarkNoExit->id,
                'design_no_id' => $isDesignNameExit->id,
                'item_id' => $isItemExit->id,
                'order_no_id' => $isOrderNoExit->id,
            ]);

            if (!empty($request->images)) {
                $jobData['images'] = $request->images;
            }

            $job = $this->jobService->createOrUpdateJob($jobData);

            if ($job) {
                return response()->json([
                    'message' => 'Job created/updated successfully.',
                    'data' => $job
                ], 201);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create/update job.',
                'error' => $e->getMessage()
            ], 500);
        }
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
