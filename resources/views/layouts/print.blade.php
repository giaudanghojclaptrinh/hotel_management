<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Hóa đơn')</title>
    <style>
        /* Print layout tuned to match provided mockup */
        html, body {
            margin: 0;
            padding: 0;
            background: #f3f4f6;
            color: #111827;
            -webkit-print-color-adjust: exact;
            font-family: 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;
        }

        .print-wrap {
            display: flex;
            justify-content: center;
            padding: 18px 12px;
        }

        .print-card {
            width: 760px;
            background: transparent;
        }

        /* The invoice card itself (invoice view renders this inner structure) */
        .invoice-inner {
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e6e7eb;
        }

        /* Header band matching mockup */
        .invoice-header {
            background: #0f1724; /* dark navy */
            color: #ffffff;
            padding: 28px 32px;
            text-align: center;
            position: relative;
        }
        .invoice-header h1 {
            margin: 0;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 28px;
            letter-spacing: 1px;
        }
        .invoice-header .subtitle {
            margin-top: 6px;
            font-size: 11px;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .invoice-badge {
            display: inline-block;
            margin-top: 12px;
            background: rgba(255,255,255,0.08);
            color: #fff;
            padding: 6px 10px;
            border-radius: 999px;
            font-family: monospace;
            font-size: 12px;
            border: 1px solid rgba(255,255,255,0.06);
        }

        /* Content area inside white card */
        .invoice-body {
            padding: 28px 32px;
            color: #111827;
            font-size: 13px;
        }

        /* Footer small text */
        .invoice-footer {
            padding: 22px 32px;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 10px; }
        thead th { color: #6b7280; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }

        /* Page config */
        @page { size: A4 portrait; margin: 10mm; }

        @media print {
            body { background: #fff; }
            .print-wrap { padding: 0; }
            .print-card { width: 100%; max-width: 760px; }
        }
        /* Neutralize view-level background/padding when rendering inside print layout */
        .invoice-inner .bg-gray-100 {
            background: transparent !important;
            min-height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .invoice-inner .max-w-3xl { max-width: 100% !important; padding: 0 !important; }
    </style>
</head>
<body>
    <div class="print-wrap">
        <div class="print-card">
            <div class="invoice-inner">
                @yield('content')
            </div>
            <div class="invoice-footer">
                @if(View::hasSection('print_footer'))
                    @yield('print_footer')
                @else
                    <div>Cảm ơn quý khách đã lựa chọn Luxury Stay Hotel &amp; Resort.</div>
                    <div>Mọi thắc mắc xin liên hệ hotline: 1900 1234 — support@luxurystay.com</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
