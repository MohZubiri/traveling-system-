<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فاتورة #{{ $transaction->reference_id }} - {{ config('app.name') }}</title>
    
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .receipt {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .receipt-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 2rem;
            border-radius: 10px 10px 0 0;
        }
        .receipt-body {
            padding: 2rem;
        }
        .receipt-footer {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0 0 10px 10px;
        }
        .table th {
            font-weight: 600;
        }
        .company-logo {
            width: 120px;
            height: auto;
        }
        @media print {
            body {
                background-color: white;
            }
            .receipt {
                box-shadow: none;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="receipt">
            <!-- Receipt Header -->
            <div class="receipt-header">
                <div class="row align-items-center">
                    <div class="col-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="company-logo mb-3">
                        <h4 class="mb-1">{{ config('app.name') }}</h4>
                        <p class="mb-0">نظام إدارة السفر والسياحة</p>
                    </div>
                    <div class="col-6 text-start">
                        <h3 class="mb-1">فاتورة رسمية</h3>
                        <p class="mb-0">#{{ $transaction->reference_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Receipt Body -->
            <div class="receipt-body">
                <!-- Customer & Transaction Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">معلومات العميل</h5>
                        <p class="mb-1"><strong>الاسم:</strong> {{ $transaction->customer->name }}</p>
                        <p class="mb-1"><strong>البريد الإلكتروني:</strong> {{ $transaction->customer->email }}</p>
                        <p class="mb-1"><strong>رقم الهاتف:</strong> {{ $transaction->customer->phone }}</p>
                        <p class="mb-0"><strong>الجنسية:</strong> {{ $transaction->customer->nationality }}</p>
                    </div>
                    <div class="col-md-6 text-md-start">
                        <h5 class="mb-3">معلومات المعاملة</h5>
                        <p class="mb-1"><strong>تاريخ المعاملة:</strong> {{ $transaction->created_at->format('Y-m-d') }}</p>
                        <p class="mb-1"><strong>وقت المعاملة:</strong> {{ $transaction->created_at->format('H:i') }}</p>
                        <p class="mb-1"><strong>رقم المرجع:</strong> {{ $transaction->reference_id }}</p>
                        <p class="mb-0">
                            <strong>الحالة:</strong>
                            <span class="badge bg-success">مكتمل</span>
                        </p>
                    </div>
                </div>

                <!-- Service Details -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>الخدمة</th>
                                <th>التفاصيل</th>
                                <th>المبلغ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{ $transaction->service_type === 'visa' ? 'تأشيرة' : 'حجز' }}
                                    @if($transaction->service_type === 'visa')
                                        ({{ $transaction->visa->type }})
                                    @else
                                        ({{ $transaction->booking->service_type }})
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->service_type === 'visa')
                                        تأشيرة {{ $transaction->visa->type }} -
                                        تاريخ التقديم: {{ $transaction->visa->submission_date }}
                                    @else
                                        حجز {{ $transaction->booking->service_type }} -
                                        التاريخ: {{ $transaction->booking->booking_date }}
                                    @endif
                                </td>
                                <td class="text-start">{{ number_format($transaction->amount, 2) }} ريال</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-start"><strong>المجموع</strong></td>
                                <td class="text-start"><strong>{{ number_format($transaction->amount, 2) }} ريال</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($transaction->description)
                    <div class="alert alert-light mb-0">
                        <strong>ملاحظات:</strong><br>
                        {{ $transaction->description }}
                    </div>
                @endif
            </div>

            <!-- Receipt Footer -->
            <div class="receipt-footer">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">
                            <strong>{{ config('app.name') }}</strong><br>
                            الرياض، المملكة العربية السعودية<br>
                            هاتف: +966 XX XXX XXXX<br>
                            البريد الإلكتروني: info@example.com
                        </p>
                    </div>
                    <div class="col-md-6 text-md-start">
                        <p class="mb-0">
                            <small class="text-muted">
                                تم إصدار هذه الفاتورة إلكترونياً وهي صالحة بدون توقيع أو ختم.
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-1"></i> طباعة الفاتورة
            </button>
            <a href="{{ route('customer.transactions.show', $transaction) }}" class="btn btn-light">
                <i class="fas fa-arrow-right me-1"></i> العودة للتفاصيل
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
