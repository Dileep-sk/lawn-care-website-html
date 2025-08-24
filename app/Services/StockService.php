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
        $query = Stock::with(['item', 'designNo', 'markNp']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('item', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('designNo', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('markNp', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $stocks = $query->get();

        $grouped = $stocks->groupBy(function ($stock) {
            return $stock->item_id . '-' . $stock->design_no_id . '-' . $stock->mark_no_id;
        })->map(function ($group) {
            $first = $group->first();
            $totalQuantity = $group->sum(function ($stock) {
                return $stock->stock_manage == 1
                    ? $stock->quantity
                    : -$stock->quantity;    
            });

            return [
                'item_name' => $first->item->name ?? null,
                'design_no' => $first->designNo->name ?? null,
                'mark_no' => $first->markNp->name ?? null,
                'quantity' => $totalQuantity,
                'status' => $totalQuantity > 0 ? 1 : 0,
            ];
        })->values();

        $currentPage = request()->get('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $grouped->forPage($currentPage, $perPage),
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginated;
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

    public function create(array $data): Stock
    {
        return Stock::create($data);
    }

    public function update(Stock $stock, array $data): Stock
    {
        $stock->update($data);
        return $stock;
    }

    public function getStcokById($id)
    {

        return Stock::find($id);
    }

    public function updateStcok(int $id, array $data)
    {
        $stock = Stock::findOrFail($id);

        $stock->update($data);

        return $stock;
    }
}
