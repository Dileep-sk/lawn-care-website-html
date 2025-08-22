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
                $q->where('company_name', 'like', "%{$search}%")
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
        return Job::create($data);
    }

    public function updateJob($id, array $data)
    {
        $job = Job::findOrFail($id);
        $job->update($data);
        return $job;
    }


}
