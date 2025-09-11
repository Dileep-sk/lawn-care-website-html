<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobImages;
use Illuminate\Http\Request;
use App\Services\StockService;
use Illuminate\Support\Facades\Storage;

class JobService
{
    protected $stockService;
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function getAllJobs(Request $request)
    {
        $query = Job::query()
            ->select(
                'order_jobs.id',
                'order_jobs.customer_id',
                'order_jobs.mark_no_id',
                'order_jobs.design_no_id',
                'order_jobs.item_id',
                'order_jobs.order_no_id',
                'order_jobs.quantity',
                'order_jobs.status',
                'design_nos.name as design_no',
                'mark_nos.name as mark_no',
                'items.name as item_name',
                'orders.order_no as order_no'
            )
            ->leftJoin('customers', 'order_jobs.customer_id', '=', 'customers.id')
            ->leftJoin('mark_nos', 'order_jobs.mark_no_id', '=', 'mark_nos.id')
            ->leftJoin('design_nos', 'order_jobs.design_no_id', '=', 'design_nos.id')
            ->leftJoin('items', 'order_jobs.item_id', '=', 'items.id')
            ->leftJoin('orders', 'order_jobs.order_no_id', '=', 'orders.id');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('mark_nos.name', 'like', "%{$search}%")
                    ->orWhere('design_nos.name', 'like', "%{$search}%")
                    ->orWhere('order_jobs.id', 'like', "%{$search}%")
                    ->orWhere('items.name', 'like', "%{$search}%")
                    ->orWhere('order_jobs.quantity', 'like', "%{$search}%")
                    ->orWhere('order_jobs.status', 'like', "%{$search}%");
            });
        }


        if ($request->filled('status')) {
            $query->where('order_jobs.status', $request->status);
        }

        $perPage = $request->input('per_page', 10);

        return $query->latest('order_jobs.created_at')->paginate($perPage);
    }


    public function updateJobStatus($id, $status)
    {


        $job = Job::find($id);

        if (!$job) {
            return false;
        }
        if ($status === Job::MOVE_JOB_IN_STOCK) {
            $stockData = [
                'item_id'       => $job->item_id,
                'design_no_id'  => $job->design_no_id,
                'mark_no_id'    => $job->mark_no_id,
                'quantity'      => $job->quantity,
                'message'       => 'Job Move in stock',
                'stock_manage'  => 1,
            ];

            $this->stockService->create($stockData);
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
        $job = Job::select(
            'order_jobs.*',
            'customers.name as customer_name',
            'mark_nos.name as mark_no',
            'design_nos.name as design_no',
            'items.name as item_name',
            'orders.order_no as order_no'
        )
            ->leftJoin('customers', 'order_jobs.customer_id', '=', 'customers.id')
            ->leftJoin('mark_nos', 'order_jobs.mark_no_id', '=', 'mark_nos.id')
            ->leftJoin('design_nos', 'order_jobs.design_no_id', '=', 'design_nos.id')
            ->leftJoin('items', 'order_jobs.item_id', '=', 'items.id')
            ->leftJoin('orders', 'order_jobs.order_no_id', '=', 'orders.id')
            ->where('order_jobs.id', $id)
            ->with('images')
            ->first();

        if ($job) {

            $job->images->transform(function ($image) {
                $image->full_url = asset('storage/job_images/' . $image->image);
                return $image;
            });
        }

        return $job;
    }

    public function createOrUpdateJob($data)
    {
        if (isset($data['job_id'])) {
            $job = Job::where('id', $data['job_id'])->first();

            if (!$job) {
                throw new \Exception('Job not found.');
            }
            if (!empty($data['images'])) {
                $this->deleteOldJobImages($job);
                $this->saveJobImages($job, $data['images']);
            }

            $job->update([
                'customer_id' => $data['customer_id'] ?? null,
                'mark_no_id' => $data['mark_no_id'] ?? null,
                'design_no_id' => $data['design_no_id'] ?? null,
                'item_id' => $data['item_id'] ?? null,
                'quantity' => $data['quantity'] ?? null,
                'order_no_id' => $data['order_no_id'] ?? null,
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
        } else {

            $job = Job::create([
                'customer_id' => $data['customer_id'] ?? null,
                'mark_no_id' => $data['mark_no_id'] ?? null,
                'design_no_id' => $data['design_no_id'] ?? null,
                'item_id' => $data['item_id'] ?? null,
                'quantity' => $data['quantity'] ?? null,
                'order_no_id' => $data['order_no_id'] ?? null,
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

            if (!empty($data['images'])) {
                $this->saveJobImages($job, $data['images']);
            }
        }

        return $job;
    }

    public function deleteOldJobImages(Job $job)
    {
        $oldImages = JobImages::where('job_id', $job->id)->get();

        foreach ($oldImages as $oldImage) {
            $imagePath = storage_path('app/public/job_images/' . $oldImage->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $oldImage->delete();
        }
    }

    public function saveJobImages(Job $job, array $images)
    {
        $directory = storage_path('app/public/job_images');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        foreach ($images as $image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                $filename = basename($image);
            } else {
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($directory, $filename);
            }

            JobImages::create([
                'job_id' => $job->id,
                'image' => $filename,
            ]);
        }
    }

    public function updateJob($id, array $data)
    {
        $job = Job::findOrFail($id);
        $job->update($data);
        return $job;
    }
}
