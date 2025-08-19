<?php

namespace App\Services;

use App\Models\Stock;

class StockService
{
    /**
     * Get paginated stocks with optional search.
     *
     * @param int $perPage
     * @param string|null $search
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getStocksPaginated(int $perPage = 10, ?string $search = null)
    {
        $query = Stock::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('mark_no', 'like', "%{$search}%")
                    ->orWhere('design_no', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function updateStockStatus($id, $status): bool
    {
        $stcok = Stock::find($id);

        if (!$stcok) {
            return false;
        }

        $stcok->status = $status;

        return $stcok->save();
    }


    public function deleteStockById($id): bool
    {
        $user = Stock::find($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }
}
