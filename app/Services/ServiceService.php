<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceService 
{
    /**
     * Get paginated stocks with optional search.
     *
     * @param int $perPage
     * @param string|null $search
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getServices(int $perPage = 10, ?string $search = null)
    {
        $query = Service::select('id', 'name', 'slug', 'banner_title', 'status');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->orWher('name', 'like', "%{$search}%")
                    ->orWher('slug', 'like', "%{$search}%")
                    ->orWher('banner_title', 'like', "%{$search}%");
            });
        }

        $paginated = $query->paginate($perPage);

        $paginated->getCollection()->transform(function ($row) {
            return [
                'id'           => $row->id ?? null,
                'name'         => $row->name ?? null,
                'slug'         => $row->slug ?? null,
                'banner_title' => $row->banner_title ?? null,
                'status'       => $row->status,
            ];
        });

        return $paginated;
    }

    public function create(array|Request $data): Service
    {
        if ($data instanceof Request) {
            $data = $data->validated();
        }

        if (isset($data['banner_image']) && $data['banner_image'] instanceof \Illuminate\Http\UploadedFile) {
            $image = $data['banner_image'];
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('services', $filename, 'public');
            $data['banner_image'] = $path;
        }

        $data['status'] = 1;
        $data['slug'] = Str::slug($data['name'], '_');

        return Service::create($data);
    }

    public function update(int $id, array $data): Service
    {
        $service = Service::findOrFail($id);
        
        if (!empty($data['banner_image'])) {
            $image = $data['banner_image'];
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('services', $filename, 'public');
            $data['banner_image'] = $path;
        } else {
            unset($data['banner_image']);
        }
        
        if (!empty($data['name'])) {
            $data['slug'] = Str::slug($data['name'], '_');
        }

        $service->update($data);

        return $service;
    }

    public function getServiceById($id)
    {
        return Service::findOrFail($id);
    }
    
}