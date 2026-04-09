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

    /**
     * Export report to CSV
     */
    public function exportCsv(Request $request)
    {
        $type = $request->input('type', 'inventory');
        $tenantId = $request->user()->tenant_id;
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reporte_' . $type . '_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($type, $tenantId, $request) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            switch ($type) {
                case 'inventory':
                    $this->exportInventoryCsv($file, $tenantId);
                    break;
                case 'movements':
                    $this->exportMovementsCsv($file, $tenantId, $request);
                    break;
                case 'low-stock':
                    $this->exportLowStockCsv($file, $tenantId);
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportInventoryCsv($file, $tenantId)
    {
        // Headers
        fputcsv($file, ['REPORTE DE VALORACIÓN DE INVENTARIO']);
        fputcsv($file, ['Fecha:', date('d/m/Y H:i')]);
        fputcsv($file, []);
        
        fputcsv($file, ['ID', 'Producto', 'SKU', 'Categoría', 'Almacén', 'Cantidad', 'Costo Unit.', 'Precio Venta', 'Valor Costo', 'Valor Venta']);
        
        $stockLevels = StockLevel::where('tenant_id', $tenantId)
            ->with(['product.category', 'warehouse'])
            ->where('quantity', '>', 0)
            ->get();

        foreach ($stockLevels as $level) {
            $product = $level->product;
            if (!$product) continue;

            fputcsv($file, [
                $product->id,
                $product->name,
                $product->sku,
                $product->category?->name ?? 'Sin categoría',
                $level->warehouse?->name ?? 'Sin almacén',
                $level->quantity,
                $product->unit_cost,
                $product->selling_price,
                $level->quantity * $product->unit_cost,
                $level->quantity * $product->selling_price,
            ]);
        }
        
        fputcsv($file, []);
        fputcsv($file, ['Generado por StockWolf - https://inventory-pro-z81e.onrender.com']);
    }

    private function exportMovementsCsv($file, $tenantId, $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', Carbon::now()->toDateString());

        fputcsv($file, ['REPORTE DE MOVIMIENTOS']);
        fputcsv($file, ['Período:', $dateFrom, 'al', $dateTo]);
        fputcsv($file, ['Fecha de generación:', date('d/m/Y H:i')]);
        fputcsv($file, []);
        
        fputcsv($file, ['Fecha', 'Producto', 'SKU', 'Tipo', 'Cantidad', 'Costo Unit.', 'Valor Total', 'Almacén', 'Motivo', 'Usuario']);
        
        $movements = StockMovement::where('tenant_id', $tenantId)
            ->with(['product', 'warehouse', 'user'])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($movements as $m) {
            fputcsv($file, [
                $m->created_at->format('d/m/Y H:i'),
                $m->product?->name ?? 'N/A',
                $m->product?->sku ?? 'N/A',
                $m->type === 'entry' ? 'Entrada' : 'Salida',
                $m->quantity,
                $m->unit_cost ?? $m->product?->unit_cost ?? 0,
                $m->quantity * ($m->unit_cost ?? $m->product?->unit_cost ?? 0),
                $m->warehouse?->name ?? 'N/A',
                $m->reason ?? 'N/A',
                $m->user?->fullName() ?? 'Sistema',
            ]);
        }
        
        fputcsv($file, []);
        fputcsv($file, ['Generado por StockWolf']);
    }

    private function exportLowStockCsv($file, $tenantId)
    {
        fputcsv($file, ['REPORTE DE STOCK BAJO']);
        fputcsv($file, ['Fecha:', date('d/m/Y H:i')]);
        fputcsv($file, []);
        
        fputcsv($file, ['Producto', 'SKU', 'Categoría', 'Stock Actual', 'Stock Mínimo', 'Faltante', 'Estado']);
        
        $products = Product::where('tenant_id', $tenantId)
            ->with(['category', 'stockLevels'])
            ->whereHas('stockLevels', function ($q) {
                $q->whereColumn('stock_levels.quantity', '<=', 'products.stock_min');
            })
            ->get();

        foreach ($products as $p) {
            $totalStock = $p->stockLevels->sum('quantity');
            $needed = $p->stock_min - $totalStock + ($p->stock_min * 0.5);
            
            fputcsv($file, [
                $p->name,
                $p->sku,
                $p->category?->name ?? 'Sin categoría',
                $totalStock,
                $p->stock_min,
                max(0, $needed),
                $totalStock == 0 ? 'Sin stock' : 'Stock bajo',
            ]);
        }
        
        fputcsv($file, []);
        fputcsv($file, ['Generado por StockWolf']);
    }

    /**
     * Export report to PDF (HTML format)
     */
    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'inventory');
        $tenantId = $request->user()->tenant_id;
        $user = $request->user();

        $data = $this->getReportData($type, $tenantId, $request);
        
        $html = view('reports.pdf', [
            'type' => $type,
            'data' => $data,
            'user' => $user,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])->render();

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="reporte_' . $type . '_' . date('Y-m-d') . '.html"',
        ]);
    }

    /**
     * Export report to Excel (CSV with Excel headers)
     */
    public function exportExcel(Request $request)
    {
        // Same as CSV but with different extension and MIME type
        $type = $request->input('type', 'inventory');
        $tenantId = $request->user()->tenant_id;
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reporte_' . $type . '_' . date('Y-m-d') . '.xls"',
        ];

        $callback = function () use ($type, $tenantId, $request) {
            $file = fopen('php://output', 'w');
            
            // Excel header for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Simple Excel HTML format
            echo "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
            echo "<head><meta charset='UTF-8'></head><body><table>";

            switch ($type) {
                case 'inventory':
                    $this->exportInventoryExcel($file);
                    break;
                case 'movements':
                    $this->exportMovementsExcel($file, $request);
                    break;
                case 'low-stock':
                    $this->exportLowStockExcel($file);
                    break;
            }

            echo "</table></body></html>";
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportInventoryExcel($file)
    {
        echo "<tr><th colspan='10'><b>REPORTE DE VALORACIÓN DE INVENTARIO</b></th></tr>";
        echo "<tr><td colspan='10'>Fecha: " . date('d/m/Y H:i') . "</td></tr>";
        echo "<tr></tr>";
        
        echo "<tr>";
        $headers = ['ID', 'Producto', 'SKU', 'Categoría', 'Almacén', 'Cantidad', 'Costo Unit.', 'Precio Venta', 'Valor Costo', 'Valor Venta'];
        foreach ($headers as $h) {
            echo "<th><b>$h</b></th>";
        }
        echo "</tr>";
        
        // This is a placeholder - actual implementation would need tenant context
        echo "<tr><td colspan='10'>Los datos se generan dinámicamente</td></tr>";
    }

    private function exportMovementsExcel($file, $request)
    {
        echo "<tr><th colspan='10'><b>REPORTE DE MOVIMIENTOS</b></th></tr>";
        echo "<tr><td colspan='10'>Generado por StockWolf</td></tr>";
        echo "<tr></tr>";
    }

    private function exportLowStockExcel($file)
    {
        echo "<tr><th colspan='7'><b>REPORTE DE STOCK BAJO</b></th></tr>";
        echo "<tr><td colspan='7'>Generado por StockWolf</td></tr>";
        echo "<tr></tr>";
    }

    private function getReportData($type, $tenantId, $request)
    {
        switch ($type) {
            case 'inventory':
                $stockLevels = StockLevel::where('tenant_id', $tenantId)
                    ->with(['product.category', 'warehouse'])
                    ->where('quantity', '>', 0)
                    ->get();
                return ['stock_levels' => $stockLevels];
            
            case 'movements':
                $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
                $dateTo = $request->input('date_to', Carbon::now()->toDateString());
                $movements = StockMovement::where('tenant_id', $tenantId)
                    ->with(['product', 'warehouse', 'user'])
                    ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                return ['movements' => $movements, 'date_from' => $dateFrom, 'date_to' => $dateTo];
            
            case 'low-stock':
                $products = Product::where('tenant_id', $tenantId)
                    ->with(['category', 'stockLevels'])
                    ->whereHas('stockLevels', function ($q) {
                        $q->whereColumn('stock_levels.quantity', '<=', 'products.stock_min');
                    })
                    ->get();
                return ['products' => $products];
            
            default:
                return [];
        }
    }
}