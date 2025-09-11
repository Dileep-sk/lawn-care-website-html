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
            ->with(['item', 'designNo', 'markNo'])
            ->groupBy('item_id', 'design_no_id', 'mark_no_id');


        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('item', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('designNo', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('markNo', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }


        $paginated = $query->paginate($perPage);

        $paginated->getCollection()->transform(function ($row) {
            return [
                'item_name'  => $row->item->name ?? null,
                'design_no'  => $row->designNo->name ?? null,
                'mark_no'    => $row->markNo->name ?? null,
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
        $stocks = Stock::with(['item', 'designNo', 'markNo'])
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
                    'mark_no' => $first->markNo->name ?? null,
                    'quantity' => $totalQuantity,
                    'status' => 1,
                ];
            }

            return null;
        })->filter()->values();

        return response()->json($grouped);
    }

    public function getOutOffStocks(string $startDate, string $endDate, int $perPage = 10)
    {
        $query = Stock::query()
            ->selectRaw('
            item_id,
            design_no_id,
            mark_no_id,
            SUM(CASE WHEN stock_manage = 1 THEN quantity ELSE -quantity END) AS total_quantity
        ')
            ->whereBetween('created_at', ["$startDate 00:00:00", "$endDate 23:59:59"])
            ->groupBy('item_id', 'design_no_id', 'mark_no_id')
            ->having('total_quantity', '<=', 0);

        $paginated = $query->paginate($perPage);

        $paginated->getCollection()->transform(function ($row) {
            return [
                'item_name'  => $row->item->name ?? null,
                'design_no'  => $row->designNo->name ?? null,
                'mark_no'    => $row->markNo->name ?? null,
                'quantity'   => (int) $row->total_quantity,
                'status'     => $row->total_quantity > 0 ? 1 : 0,
            ];
        });

        return $paginated;
    }

    public function getStockLog($search = null, $perPage = 10, $page = 1)
    {
        $query = Stock::query()
            ->join('items', 'stocks.item_id', '=', 'items.id')
            ->join('design_nos', 'stocks.design_no_id', '=', 'design_nos.id')
            ->join('mark_nos', 'stocks.mark_no_id', '=', 'mark_nos.id')
            ->select(
                'stocks.item_id',
                'stocks.design_no_id',
                'stocks.mark_no_id',
                'stocks.status',
                'stocks.quantity',
                'stocks.message',
                'stocks.stock_manage',
                'stocks.created_at',
                'items.name as item_name',
                'design_nos.name as design_no_name',
                'mark_nos.name as mark_no_name'
            )->orderBy('stocks.created_at', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('items.name', 'like', "%{$search}%")
                    ->orWhere('design_nos.name', 'like', "%{$search}%")
                    ->orWhere('mark_nos.name', 'like', "%{$search}%")
                    ->orWhere('stocks.message', 'like', "%{$search}%")
                    ->orWhere('stocks.quantity', 'like', "%{$search}%");
            });
        }

        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        return $query->paginate($perPage);
    }
}
