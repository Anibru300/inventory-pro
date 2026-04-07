<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Report: Current Inventory Valuation
     */
    public function inventoryValuation(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        // Get all stock levels with product info
        $stockLevels = StockLevel::where('tenant_id', $tenantId)
            ->with(['product', 'warehouse'])
            ->where('quantity', '>', 0)
            ->get();

        $totalValueCost = 0;
        $totalValuePrice = 0;
        $totalItems = 0;
        $byCategory = [];
        $byWarehouse = [];

        foreach ($stockLevels as $level) {
            $product = $level->product;
            if (!$product) continue;

            $costValue = $level->quantity * $product->unit_cost;
            $priceValue = $level->quantity * $product->selling_price;

            $totalValueCost += $costValue;
            $totalValuePrice += $priceValue;
            $totalItems += $level->quantity;

            // By category
            $catName = $product->category?->name ?? 'Sin categoría';
            if (!isset($byCategory[$catName])) {
                $byCategory[$catName] = [
                    'quantity' => 0,
                    'cost_value' => 0,
                    'price_value' => 0,
                ];
            }
            $byCategory[$catName]['quantity'] += $level->quantity;
            $byCategory[$catName]['cost_value'] += $costValue;
            $byCategory[$catName]['price_value'] += $priceValue;

            // By warehouse
            $whName = $level->warehouse?->name ?? 'Sin almacén';
            if (!isset($byWarehouse[$whName])) {
                $byWarehouse[$whName] = [
                    'quantity' => 0,
                    'cost_value' => 0,
                    'price_value' => 0,
                ];
            }
            $byWarehouse[$whName]['quantity'] += $level->quantity;
            $byWarehouse[$whName]['cost_value'] += $costValue;
            $byWarehouse[$whName]['price_value'] += $priceValue;
        }

        return response()->json([
            'summary' => [
                'total_products' => $stockLevels->count(),
                'total_items' => $totalItems,
                'total_cost_value' => round($totalValueCost, 2),
                'total_price_value' => round($totalValuePrice, 2),
                'potential_profit' => round($totalValuePrice - $totalValueCost, 2),
            ],
            'by_category' => $byCategory,
            'by_warehouse' => $byWarehouse,
            'stock_levels' => $stockLevels->map(function ($level) {
                return [
                    'product' => [
                        'id' => $level->product?->id,
                        'name' => $level->product?->name,
                        'sku' => $level->product?->sku,
                    ],
                    'warehouse' => $level->warehouse?->name,
                    'category' => $level->product?->category?->name,
                    'quantity' => $level->quantity,
                    'unit_cost' => $level->product?->unit_cost,
                    'selling_price' => $level->product?->selling_price,
                    'total_cost' => round($level->quantity * ($level->product?->unit_cost ?? 0), 2),
                    'total_price' => round($level->quantity * ($level->product?->selling_price ?? 0), 2),
                ];
            }),
        ]);
    }

    /**
     * Report: Stock Movements by Period
     */
    public function movementsReport(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', Carbon::now()->toDateString());

        $movements = StockMovement::where('tenant_id', $tenantId)
            ->with(['product', 'warehouse'])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $entries = $movements->where('type', 'entry');
        $exits = $movements->where('type', 'exit');

        $summary = [
            'total_movements' => $movements->count(),
            'total_entries' => $entries->count(),
            'total_exits' => $exits->count(),
            'entry_units' => $entries->sum('quantity'),
            'exit_units' => $exits->sum('quantity'),
            'balance' => $entries->sum('quantity') - $exits->sum('quantity'),
            'entry_value' => $entries->sum(function ($m) {
                return $m->quantity * ($m->unit_cost ?? 0);
            }),
            'exit_value' => $exits->sum(function ($m) {
                return $m->quantity * ($m->unit_cost ?? 0);
            }),
        ];

        // By product
        $byProduct = [];
        foreach ($movements as $m) {
            $productName = $m->product?->name ?? 'Desconocido';
            if (!isset($byProduct[$productName])) {
                $byProduct[$productName] = [
                    'entries' => 0,
                    'exits' => 0,
                    'balance' => 0,
                ];
            }
            if ($m->type === 'entry') {
                $byProduct[$productName]['entries'] += $m->quantity;
            } else {
                $byProduct[$productName]['exits'] += $m->quantity;
            }
            $byProduct[$productName]['balance'] = 
                $byProduct[$productName]['entries'] - $byProduct[$productName]['exits'];
        }

        // By day
        $byDay = [];
        foreach ($movements as $m) {
            $day = Carbon::parse($m->created_at)->format('Y-m-d');
            if (!isset($byDay[$day])) {
                $byDay[$day] = [
                    'entries' => 0,
                    'exits' => 0,
                ];
            }
            if ($m->type === 'entry') {
                $byDay[$day]['entries'] += $m->quantity;
            } else {
                $byDay[$day]['exits'] += $m->quantity;
            }
        }

        return response()->json([
            'period' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'summary' => $summary,
            'by_product' => $byProduct,
            'by_day' => $byDay,
            'movements' => $movements,
        ]);
    }

    /**
     * Report: Low Stock Products
     */
    public function lowStock(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $products = Product::where('tenant_id', $tenantId)
            ->with(['category', 'stockLevels.warehouse'])
            ->whereHas('stockLevels', function ($q) {
                $q->whereColumn('stock_levels.quantity', '<=', 'products.stock_min')
                  ->where('stock_levels.quantity', '>', 0);
            })
            ->get();

        $outOfStock = Product::where('tenant_id', $tenantId)
            ->with(['category', 'stockLevels.warehouse'])
            ->whereDoesntHave('stockLevels', function ($q) {
                $q->where('quantity', '>', 0);
            })
            ->orWhereHas('stockLevels', function ($q) {
                $q->where('quantity', '<=', 0);
            })
            ->get();

        return response()->json([
            'low_stock' => [
                'count' => $products->count(),
                'products' => $products->map(function ($p) {
                    $totalStock = $p->stockLevels->sum('quantity');
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'sku' => $p->sku,
                        'category' => $p->category?->name,
                        'current_stock' => $totalStock,
                        'min_stock' => $p->stock_min,
                        'needed' => $p->stock_min - $totalStock + ($p->stock_min * 0.5),
                    ];
                }),
            ],
            'out_of_stock' => [
                'count' => $outOfStock->count(),
                'products' => $outOfStock->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'sku' => $p->sku,
                        'category' => $p->category?->name,
                        'last_movement' => $p->stockMovements()->latest()->first()?->created_at,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Report: Top Products by Movement
     */
    public function topProducts(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $limit = $request->input('limit', 10);
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->toDateString());
        $dateTo = $request->input('date_to', Carbon::now()->toDateString());

        $topExits = StockMovement::where('tenant_id', $tenantId)
            ->where('type', 'exit')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->with('product')
            ->get();

        $topEntries = StockMovement::where('tenant_id', $tenantId)
            ->where('type', 'entry')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->with('product')
            ->get();

        return response()->json([
            'period' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'top_exits' => $topExits->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name,
                    'product_sku' => $item->product?->sku,
                    'total_quantity' => $item->total_quantity,
                ];
            }),
            'top_entries' => $topEntries->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name,
                    'product_sku' => $item->product?->sku,
                    'total_quantity' => $item->total_quantity,
                ];
            }),
        ]);
    }
}