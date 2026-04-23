<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->paginate(10);
        return view('patient.index', compact('patients'));
    }

    public function create()
    {
        return view('patient.create');
    }

    public function store(Request $request)
    {
        if ($request->has('skip')) {
            return redirect()->route('login');

             
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'phone' => 'required|string|max:50',
            'age' => 'required|integer|min:0|max:150',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'nullable|string|max:255',
            'is_verified' => 'boolean',
            'is_payment_method_verified' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'patient',
            'is_active' => true,
        ]);

       $patient = Patient::create([
            'user_id' => $user->id,
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'],
            'is_payment_method_verified' => $validated['is_payment_method_verified'] ?? false,
            'phone' => $validated['phone'],
            'dob' => $validated['age'],
            'address' => $validated['address'] ?? null,
            'is_verified' => $validated['is_verified'] ?? false,
            ]);
            session([
                'pending_patient_id' => $patient->id
            ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Registration successful.']);
        }

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    public function show(Patient $patient)
    {
        $patient->load('user');
        return view('patient.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patient.edit', compact('patient'));
    }

    // public function profile(Patient $patient)
    // {
    //     $patient->load('user');
    //     return view('patient.profile', compact('patient'));
    // }

    // public function update(UpdatePatientRequest $request, Patient $patient)
    // {
    //     $patient->update($request->validated());
    //     return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    // }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }

    public function paymentPage()
{
    return view('patient.payment-verification');
}


public function markVerified()
{
    $patient = Auth::user()->patient;

    $patient->update([
        'is_payment_method_verified' => 1
    ]);

    return response()->json(['success' => true]);
}




public function createIntent(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    $intent = PaymentIntent::create([
        'amount' => 100,
        'currency' => 'usd',
        'capture_method' => 'manual',
        'metadata' => [
            'user_id' => Auth::id(),
            'source' => $request->source // dashboard
        ]
    ]);

    return response()->json([
        'clientSecret' => $intent->client_secret
    ]);
}


public function registerIntent(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    $intent = PaymentIntent::create([
        'amount' => 100,
        'currency' => 'usd',
        'capture_method' => 'manual',
        'metadata' => [
            'source' => 'register'
        ]
    ]);

    return response()->json([
        'clientSecret' => $intent->client_secret
    ]);
}


public function markVerifiedAfterRegister(Request $request)
{
    try {
        $patientId = session('pending_patient_id');

        if (!$patientId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired or invalid registration flow'
            ], 400);
        }

        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        // Optional: verify request is really from frontend payment success
        if (!$request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ], 403);
        }

        $patient->update([
            'is_payment_method_verified' => 1,
            'is_verified' => 1
        ]);

        session()->forget('pending_patient_id');

        return response()->json([
            'success' => true,
            'message' => 'Registration payment verified successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}
}