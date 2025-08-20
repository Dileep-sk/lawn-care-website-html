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

        // Optional search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('design_no', 'like', "%{$search}%")
                    ->orWhere('order_no', 'like', "%{$search}%");
            });
        }

        // Optional status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $perPage = $request->input('per_page', 10); // default 10
        return $query->latest()->paginate($perPage);
    }

    public function getJobById($id)
    {
        return Job::findOrFail($id);
    }

    public function createJob(array $data)
    {
        return Job::create($data);
    }

    public function updateJob($id, array $data)
    {
        $job = Job::findOrFail($id);
        $job->update($data);
        return $job;
    }

    public function deleteJob($id)
    {
        $job = Job::findOrFail($id);
        return $job->delete();
    }
}
