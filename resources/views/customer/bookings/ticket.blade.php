<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تذكرة رقم {{ $ticket->ticket_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .ticket {
            border: 2px solid #000;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .ticket-number {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .details {
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .barcode {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <h1>تذكرة حجز {{ $ticket->booking->service_type === 'bus' ? 'حافلة' : 'سيارة' }}</h1>
            <div class="ticket-number">{{ $ticket->ticket_number }}</div>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="label">اسم العميل:</span>
                <span class="value">{{ $ticket->booking->customer->name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">نوع الخدمة:</span>
                <span class="value">{{ $ticket->booking->service_type === 'bus' ? 'حافلة' : 'سيارة' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">التاريخ:</span>
                <span class="value">{{ $ticket->booking->date->format('Y-m-d') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">الوقت:</span>
                <span class="value">{{ date('H:i', strtotime($ticket->booking->time)) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">موقع الانطلاق:</span>
                <span class="value">{{ $ticket->booking->location }}</span>
            </div>
            <div class="detail-row">
                <span class="label">تاريخ الإصدار:</span>
                <span class="value">{{ $ticket->issue_date->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        <div class="qr-code">
            {!! QrCode::size(200)->generate($ticket->ticket_number) !!}
        </div>

        <div class="barcode">
            {!! DNS1D::getBarcodeHTML($ticket->ticket_number, 'C128') !!}
        </div>

        <div class="footer">
            <p>هذه التذكرة صالحة ليوم واحد فقط</p>
            <p>يرجى الاحتفاظ بالتذكرة وإبرازها عند الطلب</p>
            <p>للاستفسارات: support@example.com | +966-XX-XXXXXXX</p>
        </div>
    </div>
</body>
</html>
