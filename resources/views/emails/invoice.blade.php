@component('mail::message')
# Invoice Confirmation

Dear {{ $patient->user->name }},

Thank you for your appointment payment! Your invoice is now ready and has been attached to this email.

---

## Appointment Details

**Invoice Number:** {{ $invoice->invoice_number }}  
**Date:** {{ $invoice->issued_date->format('M d, Y') }}

### Patient Information
- **Name:** {{ $patient->user->name }}
- **Email:** {{ $patient->user->email }}

### Appointment Information
- **Doctor:** {{ $doctor->user->name }}
- **Appointment Date:** {{ $appointment->appointment_date->format('M d, Y') }}
- **Appointment Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}

### Payment Details
- **Amount:** ${{ number_format($invoice->total_amount, 2) }}
- **Status:** {{ ucfirst($invoice->status) }}
- **Payment Date:** {{ $invoice->issued_date->format('M d, Y') }}

---

## Important Information

✓ Your payment has been successfully processed  
✓ Your appointment is confirmed  
✓ Your professional invoice PDF is attached  
✓ You can download and print this invoice for your records  

---

## Need Help?

If you have any questions about your invoice or appointment, please contact our support team or visit our platform.

@component('mail::button', ['url' => route('invoices.show', $invoice->id)])
View Invoice
@endcomponent

---

**Best Regards,**

**Healthcare Platform Team**

*This is an automated email. Please do not reply to this address. For support, contact us through your patient dashboard.*

@endcomponent
