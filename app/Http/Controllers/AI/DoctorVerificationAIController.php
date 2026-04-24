<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Support\Facades\Http;

class DoctorVerificationAIController extends Controller
{
    public function analyzeCertificate($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        if (!$doctor->certificate_path) {
            return response()->json([
                'success' => false,
                'message' => 'No certificate found'
            ], 400);
        }

        $imageUrl = asset('storage/' . $doctor->certificate_path);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' .config('services.openai.key'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4.1-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => "
You are a medical document verification AI.

Analyze this certificate and return ONLY valid JSON in this format:

{
  \"risk_score\": number (0-100),
  \"confidence\": number (0-100),
  \"status\": \"valid | suspicious | fake\",
  \"observations\": [
    \"point 1\",
    \"point 2\"
  ]
}

Rules:
- Be strict
- Do not add extra text
- Only return JSON
"
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imageUrl
                            ]
                        ]
                    ]
                ]
            ],
            'temperature' => 0.2
        ]);

        $data = $response->json();

        // Extract AI message safely
        $content = $data['choices'][0]['message']['content'] ?? null;

        $parsed = json_decode($content, true);

        if (!$parsed) {
            return response()->json([
                'success' => false,
                'message' => 'AI response invalid',
                'raw' => $data
            ], 500);
        }

        // Save clean AI result only
        $doctor->update([
            'ai_result' => json_encode($parsed)
        ]);

        return response()->json([
            'success' => true,
            'data' => $parsed
        ]);
    }
}