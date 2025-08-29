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
    public function getStocks(int $perPage = 10, ?string $search = null)
    {
        $query = Stock::query()
            ->selectRaw('
            item_id,
            design_no_id,
            mark_no_id,
            SUM(CASE WHEN stock_manage = 1 THEN quantity ELSE -quantity END) as total_quantity
        ')
            ->with(['item', 'designNo', 'markNp'])
            ->groupBy('item_id', 'design_no_id', 'mark_no_id');


        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('item', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('designNo', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('markNp', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }


        $paginated = $query->paginate($perPage);

        $paginated->getCollection()->transform(function ($row) {
            return [
                'item_name'  => $row->item->name ?? null,
                'design_no'  => $row->designNo->name ?? null,
                'mark_no'    => $row->markNp->name ?? null,
                'quantity'   => (int) $row->total_quantity,
                'status'     => $row->total_quantity > 0 ? 1 : 0,
            ];
        });

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

    public function availableStock($designNoId)
    {
        $stocks = Stock::with(['item', 'designNo', 'markNp'])
            ->where('design_no_id', $designNoId)
            ->get();

        $grouped = $stocks->groupBy(function ($stock) {
            return $stock->item_id . '-' . $stock->design_no_id . '-' . $stock->mark_no_id;
        })->map(function ($group) {
            $first = $group->first();
            $totalQuantity = $group->sum(function ($stock) {
                return $stock->stock_manage == 1
                    ? $stock->quantity
                    : -$stock->quantity;
            });

            if ($totalQuantity > 0) {
                return [
                    'item_name' => $first->item->name ?? null,
                    'design_no' => $first->designNo->name ?? null,
                    'mark_no' => $first->markNp->name ?? null,
                    'quantity' => $totalQuantity,
                    'status' => 1,
                ];
            }

            return null;
        })->filter()->values();

        return response()->json($grouped);
    }

    public function getOutOffStocks(int $perPage = 10, ?string $search = null)
    {
        $query = Stock::query()
            ->selectRaw('
            item_id,
            design_no_id,
            mark_no_id,
            SUM(CASE WHEN stock_manage = 1 THEN quantity ELSE -quantity END) as total_quantity
        ')
            ->with(['item', 'designNo', 'markNp'])
            ->groupBy('item_id', 'design_no_id', 'mark_no_id')
            ->having('total_quantity', '<=', 0);


        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('item', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('designNo', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('markNp', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $paginated = $query->paginate($perPage);


        $paginated->getCollection()->transform(function ($row) {
            return [
                'item_name'  => $row->item->name ?? null,
                'design_no'  => $row->designNo->name ?? null,
                'mark_no'    => $row->markNp->name ?? null,
                'quantity'   => (int) $row->total_quantity,
                'status'     => $row->total_quantity > 0 ? 1 : 0,
            ];
        });

        return $paginated;
    }
}
