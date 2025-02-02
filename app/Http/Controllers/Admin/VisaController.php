<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visa;
use App\Models\Customer;
use App\Notifications\VisaStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    public function index(Request $request)
    {
        $visas = Visa::with('customer')
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('admin.visas.index', compact('visas'));
    }

    public function show(Visa $visa)
    {
        $visa->load(['customer', 'documents']);
        return view('admin.visas.show', compact('visa'));
    }

    public function updateStatus(Request $request, Visa $visa)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,rejected',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $visa->status;
        
        $visa->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'approval_date' => in_array($request->status, ['completed', 'rejected']) ? now() : null
        ]);

        // Update related transaction status
        if ($visa->transaction) {
            $visa->transaction->update([
                'status' => $request->status === 'completed' ? 'completed' : 
                          ($request->status === 'rejected' ? 'failed' : 'pending')
            ]);
        }

        // Send notification to customer
        if ($oldStatus !== $request->status && in_array($request->status, ['completed', 'rejected'])) {
            $visa->customer->notify(new VisaStatusUpdated($visa));
        }

        return redirect()->back()->with('success', 'تم تحديث حالة التأشيرة بنجاح');
    }

    public function downloadDocument(VisaDocument $document)
    {
        return Storage::download($document->file_path, $document->original_name);
    }
}
