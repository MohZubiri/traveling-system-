<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Passport;
use App\Models\Document;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PassportController extends Controller
{
    public function index()
    {
        $passports = auth('customer')->user()
            ->passports()
            ->with(['documents'])
            ->latest()
            ->paginate(10);

        return view('customer.passports.index', compact('passports'));
    }

    public function create()
    {
        return view('customer.passports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'passport_number' => 'required|string|max:20',
            'expiry_date' => 'required|date',
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $passport = Passport::create([
            'customer_id' => auth('customer')->id(),
            'passport_number' => $request->passport_number,
            'status' => 'pending',
            'submission_date' => now(),
            'expiry_date' => $request->expiry_date,
            'notes' => $request->notes
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('passport-documents', 'public');
                
                Document::create([
                    'documentable_type' => Passport::class,
                    'documentable_id' => $passport->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }

        // Create transaction
        Transaction::create([
            'customer_id' => auth('customer')->id(),
            'service_type' => 'passport',
            'status' => 'pending',
            'amount' => 150, // Fixed fee for passport renewal
            'reference_id' => 'PSP-' . Str::random(10),
            'description' => 'تجديد جواز سفر',
            'transactionable_type' => Passport::class,
            'transactionable_id' => $passport->id
        ]);

        return redirect()->route('customer.passports.show', $passport)
            ->with('success', 'تم تقديم طلب تجديد الجواز بنجاح');
    }

    public function show(Passport $passport)
    {
        $this->authorize('view', $passport);
        $passport->load(['documents', 'transaction']);
        return view('customer.passports.show', compact('passport'));
    }

    public function edit(Passport $passport)
    {
        $this->authorize('update', $passport);

        if (!$passport->canModify()) {
            return back()->with('error', 'لا يمكن تعديل الطلب في الوقت الحالي');
        }

        return view('customer.passports.edit', compact('passport'));
    }

    public function update(Request $request, Passport $passport)
    {
        $this->authorize('update', $passport);

        if (!$passport->canModify()) {
            return back()->with('error', 'لا يمكن تعديل الطلب في الوقت الحالي');
        }

        $request->validate([
            'passport_number' => 'required|string|max:20',
            'expiry_date' => 'required|date',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $passport->update([
            'passport_number' => $request->passport_number,
            'expiry_date' => $request->expiry_date,
            'notes' => $request->notes
        ]);

        // Handle new document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('passport-documents', 'public');
                
                Document::create([
                    'documentable_type' => Passport::class,
                    'documentable_id' => $passport->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }

        return redirect()->route('customer.passports.show', $passport)
            ->with('success', 'تم تحديث طلب تجديد الجواز بنجاح');
    }

    public function downloadDocument(Document $document)
    {
        $this->authorize('view', $document->documentable);
        return response()->download(storage_path('app/public/' . $document->path));
    }
}
