<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte StockWolf</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #2E7DE8;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #0B1F3A;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header .subtitle { color: #666; font-size: 14px; }
        .meta {
            margin-bottom: 20px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .meta table { width: 100%; }
        .meta td { padding: 5px 0; }
        .meta td:first-child {
            font-weight: bold;
            width: 150px;
            color: #0B1F3A;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.data th {
            background: #0B1F3A;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table.data td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        table.data tr:nth-child(even) { background: #f9f9f9; }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
        .summary { display: flex; gap: 20px; margin-bottom: 30px; }
        .summary-box {
            flex: 1;
            background: #f8f9fa;
            border-left: 4px solid #2E7DE8;
            padding: 15px;
        }
        .summary-box h3 {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .summary-box p {
            font-size: 18px;
            font-weight: bold;
            color: #0B1F3A;
        }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-entry { background: #d4edda; color: #155724; }
        .badge-exit { background: #f8d7da; color: #721c24; }
        .badge-low { background: #fff3cd; color: #856404; }
        .badge-out { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>StockWolf - Reporte de Inventario</h1>
        <p class="subtitle">
            @switch($type)
                @case('inventory') Valoración de Inventario @break
                @case('movements') Movimientos de Stock @break
                @case('low-stock') Stock Bajo @break
                @default Reporte General @break
            @endswitch
        </p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td>Empresa:</td>
                <td>{{ $user->tenant->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Generado por:</td>
                <td>{{ $user->fullName() }}</td>
            </tr>
            <tr>
                <td>Fecha de generación:</td>
                <td>{{ $generatedAt }}</td>
            </tr>
            @if($type === 'movements' && isset($data['date_from']))
            <tr>
                <td>Período:</td>
                <td>{{ \Carbon\Carbon::parse($data['date_from'])->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($data['date_to'])->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($type === 'inventory')
        @php
            $totalCost = 0;
            $totalPrice = 0;
            $totalQty = 0;
            foreach($data['stock_levels'] as $level) {
                if($level->product) {
                    $totalCost += $level->quantity * $level->product->unit_cost;
                    $totalPrice += $level->quantity * $level->product->selling_price;
                    $totalQty += $level->quantity;
                }
            }
        @endphp
        
        <div class="summary">
            <div class="summary-box">
                <h3>Total Productos</h3>
                <p>{{ $data['stock_levels']->count() }}</p>
            </div>
            <div class="summary-box">
                <h3>Unidades Totales</h3>
                <p>{{ number_format($totalQty) }}</p>
            </div>
            <div class="summary-box">
                <h3>Valor al Costo</h3>
                <p>${{ number_format($totalCost, 2) }}</p>
            </div>
            <div class="summary-box">
                <h3>Valor de Venta</h3>
                <p>${{ number_format($totalPrice, 2) }}</p>
            </div>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Categoría</th>
                    <th>Almacén</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Costo Unit.</th>
                    <th class="text-right">Precio Venta</th>
                    <th class="text-right">Valor Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['stock_levels'] as $level)
                    @if($level->product)
                    <tr>
                        <td>{{ $level->product->name }}</td>
                        <td>{{ $level->product->sku }}</td>
                        <td>{{ $level->product->category?->name ?? 'Sin categoría' }}</td>
                        <td>{{ $level->warehouse?->name ?? 'Sin almacén' }}</td>
                        <td class="text-right">{{ number_format($level->quantity) }}</td>
                        <td class="text-right">${{ number_format($level->product->unit_cost, 2) }}</td>
                        <td class="text-right">${{ number_format($level->product->selling_price, 2) }}</td>
                        <td class="text-right">${{ number_format($level->quantity * $level->product->unit_cost, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

    @elseif($type === 'movements')
        <table class="data">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Tipo</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Valor</th>
                    <th>Almacén</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['movements'] as $movement)
                <tr>
                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $movement->product?->name ?? 'N/A' }}</td>
                    <td>{{ $movement->product?->sku ?? 'N/A' }}</td>
                    <td>
                        @if($movement->type === 'entry')
                            <span class="badge badge-entry">Entrada</span>
                        @else
                            <span class="badge badge-exit">Salida</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($movement->quantity) }}</td>
                    <td class="text-right">${{ number_format($movement->quantity * ($movement->unit_cost ?? $movement->product?->unit_cost ?? 0), 2) }}</td>
                    <td>{{ $movement->warehouse?->name ?? 'N/A' }}</td>
                    <td>{{ $movement->reason ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($type === 'low-stock')
        <table class="data">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Categoría</th>
                    <th class="text-right">Stock Actual</th>
                    <th class="text-right">Stock Mínimo</th>
                    <th class="text-right">Faltante</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['products'] as $product)
                    @php
                        $totalStock = $product->stockLevels->sum('quantity');
                        $needed = $product->stock_min - $totalStock + ($product->stock_min * 0.5);
                    @endphp
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category?->name ?? 'Sin categoría' }}</td>
                    <td class="text-right">{{ number_format($totalStock) }}</td>
                    <td class="text-right">{{ number_format($product->stock_min) }}</td>
                    <td class="text-right">{{ number_format(max(0, $needed)) }}</td>
                    <td>
                        @if($totalStock == 0)
                            <span class="badge badge-out">Sin Stock</span>
                        @else
                            <span class="badge badge-low">Stock Bajo</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>© 2026 StockWolf - Sistema ERP de Gestión de Inventarios</p>
        <p>Un producto de CJ Consultoría | https://anibru300.github.io/cj-consultoria-web/</p>
        <p>Este documento es confidencial y generado automáticamente.</p>
    </div>
</body>
</html>
