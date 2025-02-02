<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passport;
use App\Notifications\PassportStatusUpdated;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    public function index(Request $request)
    {
        $passports = Passport::with(['customer', 'documents'])
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

        return view('admin.passports.index', compact('passports'));
    }

    public function show(Passport $passport)
    {
        $passport->load(['customer', 'documents', 'transaction']);
        return view('admin.passports.show', compact('passport'));
    }

    public function updateStatus(Request $request, Passport $passport)
    {
        $request->validate([
            'status' => 'required|in:processing,ready,delivered,rejected',
            'pickup_date' => 'required_if:status,ready|nullable|date',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $passport->status;
        
        $passport->update([
            'status' => $request->status,
            'pickup_date' => $request->pickup_date,
            'notes' => $request->notes
        ]);

        // Update transaction status
        if ($passport->transaction) {
            $passport->transaction->update([
                'status' => $request->status === 'delivered' ? 'completed' : 
                          ($request->status === 'rejected' ? 'failed' : 'pending')
            ]);
        }

        // Send notification to customer
        if ($oldStatus !== $request->status) {
            $passport->customer->notify(new PassportStatusUpdated($passport));
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الجواز بنجاح');
    }

    public function report(Request $request)
    {
        $passports = Passport::with(['customer'])
            ->when($request->start_date, function ($query, $date) {
                return $query->whereDate('submission_date', '>=', $date);
            })
            ->when($request->end_date, function ($query, $date) {
                return $query->whereDate('submission_date', '<=', $date);
            })
            ->get();

        $totalPassports = $passports->count();
        $pendingPassports = $passports->where('status', 'pending')->count();
        $processingPassports = $passports->where('status', 'processing')->count();
        $readyPassports = $passports->where('status', 'ready')->count();
        $deliveredPassports = $passports->where('status', 'delivered')->count();
        $rejectedPassports = $passports->where('status', 'rejected')->count();

        return view('admin.passports.report', compact(
            'passports',
            'totalPassports',
            'pendingPassports',
            'processingPassports',
            'readyPassports',
            'deliveredPassports',
            'rejectedPassports'
        ));
    }

    public function downloadDocument(Document $document)
    {
        return response()->download(storage_path('app/public/' . $document->path));
    }

    public function previewDocument(Document $document)
    {
        return response()->file(storage_path('app/public/' . $document->path));
    }
}
