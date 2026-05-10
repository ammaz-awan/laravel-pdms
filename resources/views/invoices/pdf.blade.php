<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background: white;
            padding: 40px;
        }

        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 40px;
        }

        /* Header Section */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }

        .company-info h1 {
            font-size: 28px;
            color: #007bff;
            margin-bottom: 5px;
        }

        .company-info p {
            font-size: 13px;
            color: #666;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta .label {
            font-weight: 600;
            color: #333;
            font-size: 12px;
        }

        .invoice-meta .value {
            font-size: 14px;
            color: #007bff;
            font-weight: bold;
        }

        /* Patient & Doctor Info */
        .info-section {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-box {
            flex: 1;
        }

        .info-box h3 {
            font-size: 14px;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-box p {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        /* Appointment Details Table */
        .appointment-section {
            margin-bottom: 40px;
        }

        .appointment-section h3 {
            font-size: 14px;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .details-table td {
            padding: 12px 0;
            font-size: 13px;
        }

        .details-table .label {
            font-weight: 600;
            color: #333;
            width: 35%;
        }

        .details-table .value {
            color: #555;
        }

        /* Summary Section */
        .summary-section {
            margin: 40px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }

        .summary-row.total {
            border-top: 2px solid #007bff;
            padding-top: 15px;
            margin-top: 10px;
            font-weight: 700;
            font-size: 16px;
            color: #007bff;
        }

        .summary-label {
            color: #555;
        }

        .summary-value {
            color: #333;
            font-weight: 600;
        }

        /* Payment Status */
        .payment-status {
            text-align: center;
            padding: 20px;
            background: #e7f5e7;
            border-left: 4px solid #28a745;
            border-radius: 4px;
            margin: 30px 0;
        }

        .payment-status .status-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
        }

        .payment-status p {
            color: #155724;
            font-size: 13px;
            margin-top: 8px;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 11px;
            line-height: 1.8;
        }

        .footer-note {
            color: #666;
            font-size: 12px;
            font-style: italic;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                background: white;
            }
            .invoice-container {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>🏥 Healthcare Platform</h1>
                <p>Professional Medical Services</p>
            </div>
            <div class="invoice-meta">
                <div class="label">Invoice #:</div>
                <div class="value">{{ $invoice->invoice_number }}</div>
                <br>
                <div class="label" style="margin-top: 10px;">Issue Date:</div>
                <div class="value">{{ $invoice->issued_date->format('M d, Y') }}</div>
            </div>
        </div>

        <!-- Patient & Doctor Info -->
        <div class="info-section">
            <div class="info-box">
                <h3>Patient Information</h3>
                <p><strong>{{ $patient->user->name }}</strong></p>
                <p>{{ $patient->user->email }}</p>
                @if($patient->phone)
                    <p>Phone: {{ $patient->phone }}</p>
                @endif
                @if($patient->address)
                    <p>{{ $patient->address }}</p>
                @endif
            </div>

            <div class="info-box">
                <h3>Doctor Information</h3>
                <p><strong>Dr. {{ $doctor->user->name }}</strong></p>
                @if($doctor->specialization)
                    <p>{{ $doctor->specialization }}</p>
                @endif
                @if($doctor->license_number)
                    <p>License #: {{ $doctor->license_number }}</p>
                @endif
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="appointment-section">
            <h3>Appointment Details</h3>
            <table class="details-table">
                <tr>
                    <td class="label">Appointment Date:</td>
                    <td class="value">{{ $appointment->appointment_date->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Appointment Time:</td>
                    <td class="value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                </tr>
                <tr>
                    <td class="label">Appointment Status:</td>
                    <td class="value"><strong>{{ ucfirst($appointment->status) }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Payment Status:</td>
                    <td class="value"><strong>{{ ucfirst($invoice->status) }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Payment Summary -->
        <div class="summary-section">
            <div class="summary-row">
                <span class="summary-label">Service Fee:</span>
                <span class="summary-value">${{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tax (0%):</span>
                <span class="summary-value">$0.00</span>
            </div>
            <div class="summary-row total">
                <span class="summary-label">Total Amount:</span>
                <span class="summary-value">${{ number_format($invoice->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Payment Status Badge -->
        <div class="payment-status">
            <span class="status-badge">✓ PAYMENT CONFIRMED</span>
            <p>This appointment payment has been successfully processed and confirmed.</p>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p class="footer-note">This is an automated invoice from Healthcare Platform.</p>
            <p>Thank you for choosing our healthcare services.</p>
            <p style="margin-top: 15px; color: #999;">© {{ date('Y') }} Healthcare Platform. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
