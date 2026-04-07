<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryEvent;
use Illuminate\Http\Request;

class InventoryEventController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryEvent::where('tenant_id', $request->user()->tenant_id);

        // Filtros
        if ($request->has('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('processed')) {
            $query->where('processed', $request->boolean('processed'));
        }

        if ($request->has('notified')) {
            $query->where('notified', $request->boolean('notified'));
        }

        if ($request->has('entity_type') && $request->has('entity_id')) {
            $query->where('entity_type', $request->entity_type)
                  ->where('entity_id', $request->entity_id);
        }

        $events = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json($events);
    }

    public function show(Request $request, string $id)
    {
        $event = InventoryEvent::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($id);

        return response()->json($event);
    }

    public function markAsProcessed(Request $request, string $id)
    {
        $event = InventoryEvent::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($id);

        $event->markAsProcessed();

        return response()->json([
            'message' => 'Evento marcado como procesado',
            'data' => $event->fresh(),
        ]);
    }

    public function markAsNotified(Request $request, string $id)
    {
        $event = InventoryEvent::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($id);

        $event->markAsNotified();

        return response()->json([
            'message' => 'Evento marcado como notificado',
            'data' => $event->fresh(),
        ]);
    }

    public function getUnread(Request $request)
    {
        $events = InventoryEvent::where('tenant_id', $request->user()->tenant_id)
            ->where('notified', false)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'count' => $events->count(),
            'events' => $events,
        ]);
    }

    public function getStats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $stats = [
            'total' => InventoryEvent::where('tenant_id', $tenantId)->count(),
            'unprocessed' => InventoryEvent::where('tenant_id', $tenantId)->where('processed', false)->count(),
            'unnotified' => InventoryEvent::where('tenant_id', $tenantId)->where('notified', false)->count(),
            'by_priority' => [
                'critical' => InventoryEvent::where('tenant_id', $tenantId)->where('priority', 'critical')->count(),
                'high' => InventoryEvent::where('tenant_id', $tenantId)->where('priority', 'high')->count(),
                'medium' => InventoryEvent::where('tenant_id', $tenantId)->where('priority', 'medium')->count(),
                'low' => InventoryEvent::where('tenant_id', $tenantId)->where('priority', 'low')->count(),
            ],
            'by_type' => InventoryEvent::where('tenant_id', $tenantId)
                ->selectRaw('event_type, COUNT(*) as count')
                ->groupBy('event_type')
                ->pluck('count', 'event_type'),
        ];

        return response()->json($stats);
    }

    public function destroy(Request $request, string $id)
    {
        $event = InventoryEvent::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($id);

        $event->delete();

        return response()->json(['message' => 'Evento eliminado']);
    }
}
