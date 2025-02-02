<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Visa;
use App\Models\VisaDocument;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VisaController extends Controller
{
    public function index()
    {
        $visas = auth('customer')->user()->visas()->latest()->paginate(10);
        return view('customer.visas.index', compact('visas'));
    }

    public function create()
    {
        return view('customer.visas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:hajj,umrah,work',
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_names.*' => 'required|string'
        ]);

        $visa = new Visa([
            'customer_id' => auth('customer')->id(),
            'type' => $request->type,
            'status' => 'pending',
            'submission_date' => now(),
            'notes' => $request->notes
        ]);

        $visa->save();

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('visa-documents');
                
                VisaDocument::create([
                    'visa_id' => $visa->id,
                    'document_name' => $request->document_names[$index],
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }

        // Create transaction
        Transaction::create([
            'customer_id' => auth('customer')->id(),
            'service_type' => 'visa',
            'status' => 'pending',
            'amount' => config('visa.prices.' . $request->type, 0),
            'reference_id' => 'VISA-' . Str::random(10),
            'description' => 'طلب تأشيرة ' . $request->type,
            'transactionable_type' => Visa::class,
            'transactionable_id' => $visa->id
        ]);

        return redirect()->route('customer.visas.show', $visa)
            ->with('success', 'تم تقديم طلب التأشيرة بنجاح');
    }

    public function show(Visa $visa)
    {
        $this->authorize('view', $visa);
        return view('customer.visas.show', compact('visa'));
    }

    public function downloadDocument(VisaDocument $document)
    {
        $this->authorize('view', $document->visa);
        return Storage::download($document->file_path, $document->original_name);
    }
}
