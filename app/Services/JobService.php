<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class JobService
{
    public function getAllJobs(Request $request)
    {
        $query = Job::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('design_no', 'like', "%{$search}%")
                    ->orWhere('order_no', 'like', "%{$search}%")
                    ->orWhere('quantity', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        return $query->latest()->paginate($perPage);
    }


    public function updateJobStatus($id, $status)
    {
        $job = Job::find($id);

        if (!$job) {
            return false;
        }
        $job->status = $status;
        return $job->save();
    }


    public function deleteJobById($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return false;
        }

        return $job->delete();
    }



    public function getJobById($id)
    {
        return Job::findOrFail($id);
    }

    public function createJob(array $data)
    {
        $job = Job::create([
            'customer_name' => $data['customer_name'] ?? null,
            'design_no' => $data['design_no'] ?? null,
            'image' => isset($data['image']) ? json_encode($data['image']) : null,
            'quantity' => $data['quantity'] ?? null,
            'order_no' => $data['order_no'] ?? null,
            'status' => $data['status'] ?? null,
            'matching_1' => $data['matching_1'] ?? null,
            'matching_2' => $data['matching_2'] ?? null,
            'matching_3' => $data['matching_3'] ?? null,
            'matching_4' => $data['matching_4'] ?? null,
            'matching_5' => $data['matching_5'] ?? null,
            'matching_6' => $data['matching_6'] ?? null,
            'matching_7' => $data['matching_7'] ?? null,
            'matching_8' => $data['matching_8'] ?? null,
            'message' => $data['message'] ?? null,
        ]);

        return $job;
    }

    public function updateJob($id, array $data)
    {
        $job = Job::findOrFail($id);
        $job->update($data);
        return $job;
    }
}
