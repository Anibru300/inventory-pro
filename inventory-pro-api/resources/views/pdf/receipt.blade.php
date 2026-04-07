<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vale {{ $receipt->folio }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 30px;
        }
        .header {
            border-bottom: 3px solid #c9a962;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #0a1628;
        }
        .company-info {
            color: #666;
            font-size: 10px;
            margin-top: 5px;
        }
        .receipt-title {
            text-align: center;
            margin: 30px 0;
        }
        .receipt-title h1 {
            font-size: 28px;
            color: #0a1628;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .receipt-type {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
        }
        .type-entry {
            background: #d4edda;
            color: #155724;
        }
        .type-exit {
            background: #f8d7da;
            color: #721c24;
        }
        .folio-box {
            position: absolute;
            top: 30px;
            right: 30px;
            border: 2px solid #c9a962;
            padding: 10px 20px;
            text-align: center;
        }
        .folio-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .folio-number {
            font-size: 20px;
            font-weight: bold;
            color: #0a1628;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            width: 30%;
        }
        .info-value {
            width: 70%;
        }
        .product-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 20px;
            margin: 20px 0;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            color: #0a1628;
            margin-bottom: 5px;
        }
        .product-sku {
            color: #666;
            font-size: 11px;
        }
        .quantity-box {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px solid #c9a962;
            background: #faf8f5;
        }
        .quantity-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        .quantity-value {
            font-size: 48px;
            font-weight: bold;
            color: #0a1628;
        }
        .quantity-unit {
            font-size: 14px;
            color: #666;
        }
        .signatures {
            margin-top: 60px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .qr-placeholder {
            width: 80px;
            height: 80px;
            border: 1px dashed #ccc;
            margin: 10px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #999;
        }
        .notes {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
            font-size: 11px;
        }
        .notes-label {
            font-weight: bold;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="folio-box">
        <div class="folio-label">Folio</div>
        <div class="folio-number">{{ $receipt->folio }}</div>
    </div>

    <div class="header">
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-info">
            {{ $company['address'] }}<br>
            Tel: {{ $company['phone'] }} | Email: {{ $company['email'] }}
        </div>
    </div>

    <div class="receipt-title">
        <h1>Vale de {{ $receipt->type === 'entry' ? 'Entrada' : 'Salida' }}</h1>
        <span class="receipt-type {{ $receipt->type === 'entry' ? 'type-entry' : 'type-exit' }}">
            {{ $receipt->type === 'entry' ? 'ENTRADA DE INVENTARIO' : 'SALIDA DE INVENTARIO' }}
        </span>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell info-label">Fecha:</div>
            <div class="info-cell info-value">{{ $date }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Almacén:</div>
            <div class="info-cell info-value">{{ $receipt->warehouse?->name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Referencia:</div>
            <div class="info-cell info-value">{{ $receipt->reference_number ?? 'N/A' }}</div>
        </div>
        @if($receipt->recipient_name)
        <div class="info-row">
            <div class="info-cell info-label">Entregado a:</div>
            <div class="info-cell info-value">{{ $receipt->recipient_name }}</div>
        </div>
        @endif
        @if($receipt->recipient_department)
        <div class="info-row">
            <div class="info-cell info-label">Departamento:</div>
            <div class="info-cell info-value">{{ $receipt->recipient_department }}</div>
        </div>
        @endif
    </div>

    <div class="product-box">
        <div class="product-name">{{ $receipt->product?->name ?? 'Producto no disponible' }}</div>
        <div class="product-sku">SKU: {{ $receipt->product?->sku ?? 'N/A' }}</div>
    </div>

    <div class="quantity-box">
        <div class="quantity-label">Cantidad</div>
        <div class="quantity-value">{{ $receipt->quantity }}</div>
        <div class="quantity-unit">unidades</div>
    </div>

    @if($receipt->unit_cost)
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell info-label">Costo Unitario:</div>
            <div class="info-cell info-value">${{ number_format($receipt->unit_cost, 2) }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Valor Total:</div>
            <div class="info-cell info-value">${{ number_format($receipt->total, 2) }}</div>
        </div>
    </div>
    @endif

    @if($receipt->notes)
    <div class="notes">
        <span class="notes-label">Notas:</span><br>
        {{ $receipt->notes }}
    </div>
    @endif

    <div class="signatures">
        <div class="signature-box">
            <div class="qr-placeholder">QR<br>Validación</div>
            <div class="signature-line">
                <strong>{{ $receipt->creator?->name ?? 'Sistema' }}</strong><br>
                <small>Entregó</small>
            </div>
        </div>
        <div class="signature-box">
            @if($receipt->recipient_signature)
                <img src="{{ $receipt->recipient_signature }}" style="max-height: 80px; margin: 10px auto;">
            @else
                <div class="qr-placeholder">Espacio para<br>Firma</div>
            @endif
            <div class="signature-line">
                <strong>{{ $receipt->recipient_name ?? '_________________' }}</strong><br>
                <small>Recibió</small>
            </div>
        </div>
    </div>

    <div class="footer">
        Este documento es un comprobante oficial de {{ $receipt->type === 'entry' ? 'entrada' : 'salida' }} de mercancía.<br>
        Generado por Inventory Pro - {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>