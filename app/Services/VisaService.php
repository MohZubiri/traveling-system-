<?php

namespace App\Services;

use App\Models\Visa;
use App\Models\VisaDocument;
use App\Models\Transaction;
use App\Notifications\VisaStatusUpdated;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VisaService
{
    public function createVisaApplication(array $data, $customerId)
    {
        try {
            \DB::beginTransaction();

            // Calculate visa cost based on type and duration
            $cost = $this->calculateVisaCost($data['type'], $data['duration'], $data['entry_type']);

            // Create visa application
            $visa = Visa::create([
                'customer_id' => $customerId,
                'type' => $data['type'],
                'passport_number' => $data['passport_number'],
                'passport_expiry' => $data['passport_expiry'],
                'duration' => $data['duration'],
                'entry_type' => $data['entry_type'],
                'purpose' => $data['purpose'],
                'status' => 'pending',
                'cost' => $cost,
                'previous_visa' => $data['previous_visa'] ?? false,
                'previous_visa_number' => $data['previous_visa_number'] ?? null,
                'emergency_contact' => $data['emergency_contact']
            ]);

            // Handle document uploads
            if (isset($data['documents'])) {
                foreach ($data['documents'] as $document) {
                    $path = $document->store('visa-documents/' . $visa->id, 'public');
                    
                    VisaDocument::create([
                        'visa_id' => $visa->id,
                        'name' => $document->getClientOriginalName(),
                        'path' => $path,
                        'type' => $document->getClientOriginalExtension(),
                        'size' => $document->getSize()
                    ]);
                }
            }

            // Create transaction
            Transaction::create([
                'customer_id' => $customerId,
                'service_type' => 'visa',
                'amount' => $cost,
                'status' => 'pending',
                'reference_id' => 'VSA-' . Str::random(10),
                'description' => 'طلب تأشيرة ' . $this->getVisaTypeInArabic($data['type']),
                'transactionable_type' => Visa::class,
                'transactionable_id' => $visa->id
            ]);

            \DB::commit();
            return $visa;

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    protected function calculateVisaCost($type, $duration, $entryType)
    {
        $baseCost = [
            'tourist' => 300,
            'business' => 500,
            'student' => 200,
            'work' => 800
        ][$type];

        // Additional cost for longer duration
        $durationCost = ceil($duration / 30) * 50;

        // Multiple entry cost
        $entryCost = $entryType === 'multiple' ? 200 : 0;

        return $baseCost + $durationCost + $entryCost;
    }

    protected function getVisaTypeInArabic($type)
    {
        return [
            'tourist' => 'سياحية',
            'business' => 'عمل',
            'student' => 'دراسية',
            'work' => 'عمل'
        ][$type];
    }

    public function updateVisaStatus(Visa $visa, array $data)
    {
        try {
            \DB::beginTransaction();

            $visa->update([
                'status' => $data['status'],
                'admin_notes' => $data['notes'] ?? null,
                'rejection_reason' => $data['status'] === 'rejected' ? ($data['rejection_reason'] ?? null) : null,
                'issue_date' => $data['status'] === 'approved' ? now() : null,
                'expiry_date' => $data['status'] === 'approved' ? now()->addDays($visa->duration) : null
            ]);

            if ($data['status'] === 'approved') {
                $visa->transaction->update(['status' => 'completed']);
            } elseif ($data['status'] === 'rejected') {
                $visa->transaction->update(['status' => 'failed']);
            }

            // Send notification
            $visa->customer->notify(new VisaStatusUpdated($visa));

            \DB::commit();
            return $visa;

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public function validateDocuments($documents)
    {
        $totalSize = 0;
        $maxSize = 20 * 1024 * 1024; // 20MB total
        
        foreach ($documents as $document) {
            $totalSize += $document->getSize();
            
            if (!in_array($document->getClientOriginalExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
                throw new \Exception('نوع الملف غير مدعوم: ' . $document->getClientOriginalName());
            }
        }

        if ($totalSize > $maxSize) {
            throw new \Exception('الحجم الإجمالي للملفات يتجاوز 20 ميجابايت');
        }
    }
}
