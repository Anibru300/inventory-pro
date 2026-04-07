<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReceiptController extends Controller
{
    /**
     * List all receipts
     */
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        $query = Receipt::where('tenant_id', $tenantId)
            ->with(['product', 'warehouse', 'creator'])
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by folio
        if ($request->has('folio')) {
            $query->where('folio', 'like', '%' . $request->folio . '%');
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $receipts = $query->paginate($request->per_page ?? 25);

        return response()->json($receipts);
    }

    /**
     * Get single receipt
     */
    public function show(Receipt $receipt)
    {
        return response()->json($receipt->load(['product', 'warehouse', 'creator', 'stockMovement']));
    }

    /**
     * Generate PDF for receipt
     */
    public function generatePdf(Receipt $receipt)
    {
        $receipt->load(['product', 'warehouse', 'creator']);
        
        // Mark as printed
        if (!$receipt->printed_at) {
            $receipt->update(['printed_at' => now()]);
        }

        $data = [
            'receipt' => $receipt,
            'company' => [
                'name' => $receipt->tenant->name ?? 'CJ Consultoría',
                'address' => 'Dirección de la empresa',
                'phone' => 'Teléfono',
                'email' => 'email@empresa.com',
            ],
            'date' => Carbon::parse($receipt->created_at)->format('d/m/Y H:i'),
        ];

        $pdf = PDF::loadView('pdf.receipt', $data);
        $pdf->setPaper('letter', 'portrait');

        $filename = 'vale_' . $receipt->folio . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Preview PDF (return base64 for display)
     */
    public function previewPdf(Receipt $receipt)
    {
        $receipt->load(['product', 'warehouse', 'creator']);

        $data = [
            'receipt' => $receipt,
            'company' => [
                'name' => $receipt->tenant->name ?? 'CJ Consultoría',
                'address' => 'Dirección de la empresa',
                'phone' => 'Teléfono',
                'email' => 'email@empresa.com',
            ],
            'date' => Carbon::parse($receipt->created_at)->format('d/m/Y H:i'),
        ];

        $pdf = PDF::loadView('pdf.receipt', $data);
        $pdf->setPaper('letter', 'portrait');

        $base64 = base64_encode($pdf->output());

        return response()->json([
            'pdf_base64' => $base64,
            'folio' => $receipt->folio,
        ]);
    }

    /**
     * Create receipt from stock movement (called automatically)
     */
    public static function createFromMovement(StockMovement $movement, array $extraData = [])
    {
        $folio = Receipt::generateFolio($movement->type);

        $receipt = Receipt::create([
            'stock_movement_id' => $movement->id,
            'product_id' => $movement->product_id,
            'warehouse_id' => $movement->warehouse_id,
            'folio' => $folio,
            'type' => $movement->type,
            'quantity' => $movement->quantity,
            'unit_cost' => $movement->unit_cost,
            'reference_number' => $movement->reference_number,
            'notes' => $movement->notes,
            'recipient_name' => $extraData['recipient_name'] ?? null,
            'recipient_department' => $extraData['recipient_department'] ?? null,
            'created_by' => $movement->created_by,
            'status' => 'completed',
        ]);

        return $receipt;
    }

    /**
     * Update recipient info
     */
    public function updateRecipient(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'recipient_name' => 'nullable|string|max:255',
            'recipient_department' => 'nullable|string|max:255',
            'recipient_signature' => 'nullable|string',
        ]);

        $receipt->update($validated);

        return response()->json($receipt);
    }

    /**
     * Get statistics
     */
    public function statistics(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $today = Receipt::where('tenant_id', $tenantId)
            ->whereDate('created_at', today())
            ->count();

        $week = Receipt::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $month = Receipt::where('tenant_id', $tenantId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pending = Receipt::where('tenant_id', $tenantId)
            ->whereNull('printed_at')
            ->count();

        return response()->json([
            'today' => $today,
            'week' => $week,
            'month' => $month,
            'pending_print' => $pending,
        ]);
    }
}