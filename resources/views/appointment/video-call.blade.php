@extends('layouts.layout')

@section('title', 'Video Consultation')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
/* ================================================================
   VIDEO CALL PAGE — Google Meet Style UI
   ================================================================ */
:root {
    --primary: #1a6fc4;
    --primary-dk: #145aa0;
    --bg-dark: #0f0f0f;
    --bg-card: #1a1a1a;
    --bg-light: #2a2a2a;
    --text-primary: #ffffff;
    --text-secondary: #e0e0e0;
    --border-color: #404040;
    --success: #198754;
    --danger: #dc3545;
    --warning: #ffc107;
}

/* Light mode (override for light theme) */
body.light-theme {
    --bg-dark: #ffffff;
    --bg-card: #f5f5f5;
    --bg-light: #e8e8e8;
    --text-primary: #202124;
    --text-secondary: #5f6368;
    --border-color: #dadce0;
}

* {
    box-sizing: border-box;
}

html, body {
    margin: 0;
    padding: 0;
    background: var(--bg-dark);
    color: var(--text-primary);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

/* ===== FULLSCREEN CALL CONTAINER ===== */
.video-call-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: var(--bg-dark);
    z-index: 1000;
}

/* ===== TOP BAR ===== */
.call-header {
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border-color);
    height: 56px;
    min-height: 56px;
    flex-shrink: 0;
}

.call-header-title {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
}

.call-header-title .title-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.call-header-title .title-main {
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.call-header-title .title-meta {
    font-size: 0.8rem;
    color: var(--text-secondary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.call-header-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

.call-timer {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    font-variant-numeric: tabular-nums;
    letter-spacing: 0.5px;
}

.call-timer i {
    font-size: 1rem;
    animation: pulse-timer 2s infinite;
}

@keyframes pulse-timer {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.call-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    background: rgba(25, 135, 84, 0.2);
    border-radius: 6px;
    font-size: 0.8rem;
    color: var(--success);
    font-weight: 600;
}

.call-status.ended {
    background: rgba(108, 117, 125, 0.2);
    color: #adb5bd;
}

/* ===== MAIN VIDEO AREA ===== */
.call-content {
    flex: 1;
    position: relative;
    overflow: hidden;
    background: #000000;
}

.video-main {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000000;
}

.video-main video,
.video-main div[id] {
    width: 100% !important;
    height: 100% !important;
    object-fit: contain;
    max-width: 100%;
    max-height: 100%;
}

.video-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 16px;
    color: #808080;
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    z-index: 1;
}

.video-placeholder i {
    font-size: 4rem;
    opacity: 0.6;
}

.video-placeholder span {
    font-size: 1rem;
    opacity: 0.7;
}

/* ===== FLOATING SELF VIDEO ===== */
.video-self-container {
    position: absolute;
    bottom: 100px;
    right: 16px;
    width: 220px;
    height: 165px;
    border-radius: 12px;
    overflow: hidden;
    background: #000000;
    border: 2px solid var(--border-color);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    z-index: 100;
    transition: all 0.3s ease;
}

.video-self-container:hover {
    transform: scale(1.03);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
}

.video-self-container video,
.video-self-container div[id] {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

.video-self-label {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 4px;
    z-index: 10;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100px;
}

/* ===== BOTTOM CONTROL BAR ===== */
.call-controls {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    padding: 12px 16px;
    border-radius: 12px;
    z-index: 200;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.control-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    background: #3d3d3d;
    color: white;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.control-btn:hover {
    background: #4d4d4d;
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.control-btn.active {
    background: #27c752;
}

.control-btn.active:hover {
    background: #1fb842;
}

.control-btn.off,
.control-btn.muted {
    background: #da3633;
}

.control-btn.off:hover,
.control-btn.muted:hover {
    background: #d23d3a;
}

.control-btn.end-call {
    background: #ea4335;
    width: 56px;
    height: 56px;
    font-size: 1.4rem;
}

.control-btn.end-call:hover {
    background: #d33827;
}

.control-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

/* Tooltip */
.control-btn[title]::after {
    content: attr(title);
    position: absolute;
    bottom: -32px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
}

.control-btn:hover[title]::after {
    opacity: 1;
}

/* ===== PRESCRIPTION PANEL (Doctor Only - Sidebar) ===== */
.rx-sidebar {
    position: fixed;
    right: -400px;
    top: 0;
    width: 400px;
    height: 100vh;
    background: var(--bg-card);
    border-left: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    transition: right 0.3s ease;
    z-index: 1200;
    box-shadow: -2px 0 16px rgba(0, 0, 0, 0.3);
}

.rx-sidebar.open {
    right: 0;
}

.rx-toggle-btn {
    position: fixed;
    bottom: 20px;
    right: 16px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    border: none;
    background: var(--primary);
    color: white;
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 1190;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.rx-toggle-btn:hover {
    background: var(--primary-dk);
    transform: scale(1.1);
}

.rx-sidebar.open ~ .rx-toggle-btn {
    opacity: 0;
    pointer-events: none;
}

.rx-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dk) 100%);
    color: white;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border-color);
    min-height: 56px;
}

.rx-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.rx-header .close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.rx-header .close-btn:hover {
    transform: scale(1.2);
}

.rx-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.rx-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.rx-field label {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.rx-field textarea,
.rx-field input {
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 0.88rem;
    color: var(--text-primary);
    resize: vertical;
    transition: all 0.2s;
    font-family: inherit;
}

.rx-field textarea::placeholder,
.rx-field input::placeholder {
    color: var(--text-secondary);
    opacity: 0.6;
}

.rx-field textarea:focus,
.rx-field input:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--bg-dark);
    box-shadow: 0 0 0 3px rgba(26, 111, 196, 0.1);
}

.med-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.med-row {
    display: grid;
    grid-template-columns: 1fr 80px 80px 32px;
    gap: 6px;
    align-items: center;
}

.med-row input {
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    padding: 6px 8px;
    font-size: 0.82rem;
    color: var(--text-primary);
    font-family: inherit;
}

.med-row input:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--bg-dark);
}

.btn-add-med {
    background: none;
    border: 2px dashed var(--primary);
    color: var(--primary);
    border-radius: 6px;
    padding: 8px;
    font-size: 0.82rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    transition: all 0.2s;
    margin-top: 8px;
}

.btn-add-med:hover {
    background: rgba(26, 111, 196, 0.1);
}

.btn-del-med {
    background: none;
    border: none;
    color: var(--danger);
    cursor: pointer;
    font-size: 1rem;
    padding: 0;
    transition: transform 0.2s;
}

.btn-del-med:hover {
    transform: scale(1.2);
}

.rx-footer {
    border-top: 1px solid var(--border-color);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.btn-save-rx {
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 0.88rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
    white-space: nowrap;
}

.btn-save-rx:hover:not(:disabled) {
    background: var(--primary-dk);
}

.btn-save-rx:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.save-status {
    font-size: 0.75rem;
    color: var(--text-secondary);
    white-space: nowrap;
}

.save-status.ok {
    color: var(--success);
}

.save-status.err {
    color: var(--danger);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .call-header-title .title-meta {
        display: none;
    }

    .video-self-container {
        width: 140px;
        height: 105px;
        bottom: 90px;
        right: 12px;
    }

    .call-controls {
        bottom: 16px;
        padding: 10px 12px;
        gap: 8px;
    }

    .control-btn {
        width: 44px;
        height: 44px;
        font-size: 1rem;
    }

    .control-btn.end-call {
        width: 52px;
        height: 52px;
    }

    .rx-sidebar {
        width: 100%;
        right: -100%;
    }
}

/* ===== THEME AGNOSTIC FIXES ===== */
@media (prefers-color-scheme: light) {
    body:not(.light-theme) {
        --bg-dark: #ffffff;
        --bg-card: #f5f5f5;
        --bg-light: #e8e8e8;
        --text-primary: #202124;
        --text-secondary: #5f6368;
        --border-color: #dadce0;
    }
}
</style>
@endpush

@section('content')
<div class="video-call-container">
    <!-- Top Bar -->
    <div class="call-header">
        <div class="call-header-title">
            <div class="title-text">
                <div class="title-main">
                    @if (auth()->user()->role === 'doctor')
                        Patient. {{ $appointment->patient->user->name }}
                    @else
                        Dr. {{ $appointment->doctor->user->name }}
                    @endif
                </div>

                
                <div class="title-meta">
                    {{ $appointment->appointment_date->format('M d') }} · 
                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                </div>
            </div>
        </div>

        <div class="call-header-right">
            <div class="call-timer">
                <i class="ti ti-clock"></i>
                <span id="callTimer">00:00</span>
            </div>
            <div class="call-status" id="callStatus">
                <span id="statusDot">●</span>
                <span id="statusText">Live</span>
            </div>
        </div>
    </div>

    <!-- Main Video Area -->
    <div class="call-content">
        <!-- Remote User Video (Main) -->
        <div class="video-main" id="remote-video">
            <div class="video-placeholder" id="remotePlaceholder">
                <i class="ti ti-user-circle"></i>
                <span>Waiting for {{ $callData['is_doctor'] ? 'Patient' : 'Doctor' }}…</span>
            </div>
        </div>

        <!-- Self Video (Floating) -->
        <div class="video-self-container" id="local-video">
            <div class="video-placeholder" id="localPlaceholder">
                <i class="ti ti-user-circle"></i>
            </div>
            <div class="video-self-label">
                {{ $callData['is_doctor'] ? 'You' : 'You' }}
            </div>
        </div>

        <!-- Control Bar (Bottom Center) -->
        <div class="call-controls">
            <button class="control-btn active" id="btnMic" title="Mute/Unmute">
                <i class="ti ti-microphone" id="micIcon"></i>
            </button>
            <button class="control-btn active" id="btnCam" title="Camera On/Off">
                <i class="ti ti-video" id="camIcon"></i>
            </button>
            <button class="control-btn end-call" id="btnEnd" title="End Call">
                <i class="ti ti-phone-off"></i>
            </button>
        </div>
    </div>
</div>

<!-- Prescription Sidebar (Doctor Only) -->
@if($callData['is_doctor'])
<div class="rx-sidebar" id="rxSidebar">
    <div class="rx-header">
        <h5>
            <i class="ti ti-prescription"></i>
            Prescription
        </h5>
        <button class="close-btn" onclick="document.getElementById('rxSidebar').classList.remove('open')">
            <i class="ti ti-x"></i>
        </button>
    </div>

    <form id="rxForm" class="rx-body" autocomplete="off">
        <div class="rx-field">
            <label>Diagnosis</label>
            <textarea id="rxDiagnosis" rows="3"
                placeholder="Enter diagnosis…">{{ $appointment->prescription->diagnosis ?? '' }}</textarea>
        </div>

        <div class="rx-field">
            <label>Medicines</label>
            <div class="med-list" id="medList">
                @forelse($appointment->prescription->medicines ?? [] as $med)
                <div class="med-row">
                    <input type="text" placeholder="Medicine name" value="{{ $med['name'] ?? '' }}" data-field="name">
                    <input type="text" placeholder="Dosage" value="{{ $med['dosage'] ?? '' }}" data-field="dosage">
                    <input type="text" placeholder="Duration" value="{{ $med['duration'] ?? '' }}" data-field="duration">
                    <button type="button" class="btn-del-med" title="Remove">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
                @empty
                @endforelse
            </div>
            <button type="button" class="btn-add-med" id="btnAddMed">
                <i class="ti ti-plus"></i> Add Medicine
            </button>
        </div>

        <div class="rx-field">
            <label>Additional Notes</label>
            <textarea id="rxNotes" rows="3"
                placeholder="Additional notes…">{{ $appointment->prescription->notes ?? '' }}</textarea>
        </div>
    </form>

    <div class="rx-footer">
        <button class="btn-save-rx" id="btnSaveRx">
            <i class="ti ti-device-floppy"></i> Save
        </button>
        <span class="save-status" id="saveStatus"></span>
    </div>
</div>

<!-- Prescription Toggle Button (Doctor Only) -->
<button class="rx-toggle-btn" id="rxToggleBtn" title="Prescription">
    <i class="ti ti-prescription"></i>
</button>
@endif
@endsection

@push('scripts')
{{-- External Libraries --}}
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

<script>
/* ================================================================
   CONFIG (injected from PHP)
   ================================================================ */
const AGORA_APP_ID    = @json($callData['app_id']);
const AGORA_CHANNEL   = @json($callData['channel_name']);
const AGORA_TOKEN     = @json($callData['token']);
const AGORA_UID       = @json($callData['uid']);
const IS_DOCTOR       = @json($callData['is_doctor']);
const EXPIRES_AT      = @json($callData['expires_at']);   // unix ts
const APPT_ID         = @json($appointment->id);
const CSRF_TOKEN      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const END_CALL_URL    = "{{ route('appointments.end-call', ['id' => $appointment->id]) }}";
const RX_STORE_URL    = "{{ route('appointments.prescription.store', ['id' => $appointment->id]) }}";
const RX_FETCH_URL    = "{{ route('appointments.prescription.show', ['id' => $appointment->id]) }}";
const APPT_SHOW_URL   = "{{ route('appointments.show', $appointment) }}";

// ========== 30-MINUTE HARD CALL DURATION ==========
const MAX_CALL_DURATION_MS = 30 * 60 * 1000; // 30 minutes in milliseconds
let callStartTime = null;
let callDurationTimer = null;
let callEnded = false;

/* ================================================================
   TOASTR CONFIGURATION
   ================================================================ */
toastr.options = {
    positionClass: 'toast-top-right',
    timeOut: 4000,
    extendedTimeOut: 2000,
    progressBar: true,
    closeButton: true,
};

/* ================================================================
   TIMER (displays elapsed time & manages 30-min auto-end)
   ================================================================ */
let startTime = Date.now();
const timerEl = document.getElementById('callTimer');

function updateTimer() {
    if (!callStartTime) return;
    
    const elapsed = Date.now() - callStartTime;
    const m = String(Math.floor(elapsed / 60000)).padStart(2, '0');
    const s = String(Math.floor((elapsed % 60000) / 1000)).padStart(2, '0');
    timerEl.textContent = m + ':' + s;

    // Check if 30 minutes have passed
    if (elapsed >= MAX_CALL_DURATION_MS && !callEnded) {
        callEnded = true;
        handleCallDurationExpired();
    }
}

function startCallTimer() {
    callStartTime = Date.now();
    callDurationTimer = setInterval(updateTimer, 1000);
    updateTimer(); // Update immediately
}

function stopCallTimer() {
    if (callDurationTimer) {
        clearInterval(callDurationTimer);
        callDurationTimer = null;
    }
}

function handleCallDurationExpired() {
    stopCallTimer();
    
    Swal.fire({
        title: 'Call Ended',
        text: 'Your 30-minute session has ended.',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Return to Appointment',
    }).then(() => {
        window.location.href = APPT_SHOW_URL;
    });

    endCallImmediately();
}

/* ================================================================
   AGORA RTC
   ================================================================ */
const client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });
let localTracks = { audio: null, video: null };
let micMuted = false;
let camOff   = false;

async function subscribeToRemoteUser(user, mediaType) {
    try {
        await client.subscribe(user, mediaType);

        if (mediaType === 'video' && user.videoTrack) {
            const remoteBox = document.getElementById('remote-video');
            remoteBox.querySelector('.video-placeholder')?.remove();
            user.videoTrack.play('remote-video');
        }

        if (mediaType === 'audio' && user.audioTrack) {
            user.audioTrack.play();
        }
    } catch (error) {
        console.error('Could not subscribe to remote user:', {
            uid: user.uid,
            mediaType,
            error,
        });
    }
}

function registerAgoraEvents() {
    client.on('user-published', subscribeToRemoteUser);

    client.on('user-unpublished', (user, mediaType) => {
        if (mediaType === 'video') {
            const remoteBox = document.getElementById('remote-video');
            if (!remoteBox.querySelector('.video-placeholder')) {
                remoteBox.innerHTML = `
                    <div class="video-placeholder">
                        <i class="ti ti-user-circle"></i>
                        <span>Camera paused</span>
                    </div>`;
            }
        }
    });

    client.on('user-left', () => {
        const remoteBox = document.getElementById('remote-video');
        remoteBox.innerHTML = `
            <div class="video-placeholder">
                <i class="ti ti-user-circle"></i>
                <span>Participant left</span>
            </div>`;
    });
}

async function initAgora() {
    // Validate required config before joining
    if (!AGORA_APP_ID) {
        console.error('❌ FATAL: Agora App ID is missing or empty!');
        toastr.error('Agora App ID is not configured. Please check your .env file.');
        return;
    }

    if (!AGORA_TOKEN) {
        console.error('❌ FATAL: Agora Token is missing or empty!');
        toastr.error('Agora authentication token is missing.');
        return;
    }

    try {
        registerAgoraEvents();

        await client.join(AGORA_APP_ID, AGORA_CHANNEL, AGORA_TOKEN, AGORA_UID);
        
        // Start the 30-minute call timer immediately after joining
        startCallTimer();
        toastr.success('Connected to video call');

        // Subscribe to remote users already in the channel before local device setup.
        for (const user of client.remoteUsers) {
            if (user.hasVideo) {
                await subscribeToRemoteUser(user, 'video');
            }
            if (user.hasAudio) {
                await subscribeToRemoteUser(user, 'audio');
            }
        }

        try {
            [localTracks.audio, localTracks.video] =
                await AgoraRTC.createMicrophoneAndCameraTracks();

            const localBox = document.getElementById('local-video');
            localBox.querySelector('.video-placeholder')?.remove();
            localTracks.video.play('local-video');

            await client.publish([localTracks.audio, localTracks.video]);
        } catch (deviceError) {
            console.error('Local camera/microphone setup failed:', deviceError);
            toastr.warning('Camera or microphone could not start, but you can still watch the call.');
        }

        // Start server-side session polling
        startStatusPolling();
        
    } catch (error) {
        console.error('❌ Agora initialization failed:', error);
        const errorMsg = error?.message || String(error);
        toastr.error('Video call connection failed: ' + errorMsg);
        throw error;
    }
}

async function handleRemoteUser(user) {
    if (user.hasVideo) {
        await subscribeToRemoteUser(user, 'video');
    }
    if (user.hasAudio) {
        await subscribeToRemoteUser(user, 'audio');
    }
}

initAgora().catch(err => {
    console.error('❌ Agora init error:', err);
    toastr.error('Could not connect to video call: ' + err.message);
});

/* ================================================================
   CONTROL BUTTONS
   ================================================================ */

// Mute / unmute mic
document.getElementById('btnMic').addEventListener('click', async () => {
    try {
        micMuted = !micMuted;
        await localTracks.audio?.setMuted(micMuted);
        const btn  = document.getElementById('btnMic');
        const icon = document.getElementById('micIcon');
        btn.classList.toggle('muted', micMuted);
        icon.className = micMuted ? 'ti ti-microphone-off' : 'ti ti-microphone';
    } catch (err) {
        toastr.error('Could not toggle microphone: ' + err.message);
    }
});

// Toggle camera
document.getElementById('btnCam').addEventListener('click', async () => {
    try {
        camOff = !camOff;
        await localTracks.video?.setMuted(camOff);
        const btn  = document.getElementById('btnCam');
        const icon = document.getElementById('camIcon');
        btn.classList.toggle('off', camOff);
        icon.className = camOff ? 'ti ti-video-off' : 'ti ti-video';
    } catch (err) {
        toastr.error('Could not toggle camera: ' + err.message);
    }
});

// End call (doctor only or patients can leave)
document.getElementById('btnEnd')?.addEventListener('click', async () => {
    Swal.fire({
        title: 'Leave Call?',
        text: IS_DOCTOR ? 'End this consultation for both participants?' : 'Leave this call?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Leave',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
    }).then(async (result) => {
        if (!result.isConfirmed) return;
        
        try {
            await leaveCall();
            if (IS_DOCTOR) {
                await fetch(END_CALL_URL, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                });
            }
            handleCallEnded();
        } catch (err) {
            toastr.error('Error ending call: ' + err.message);
        }
    });
});

async function endCallImmediately() {
    try {
        await leaveCall();
        if (IS_DOCTOR) {
            await fetch(END_CALL_URL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
            }).catch(() => {}); // Ignore errors on auto-end
        }
    } catch (err) {
        console.error('Error in endCallImmediately:', err);
    }
}

async function leaveCall() {
    stopCallTimer();
    localTracks.audio?.close();
    localTracks.video?.close();
    await client.leave();
}

function handleCallEnded() {
    document.getElementById('callStatusPill').className = 'status-pill status-ended';
    document.getElementById('callStatusPill').textContent = '● Ended';
    document.getElementById('callEndedOverlay').classList.add('show');
    
    // Disable all controls
    document.getElementById('btnMic').disabled = true;
    document.getElementById('btnCam').disabled = true;
    document.getElementById('btnEnd').disabled = true;
}

/* ================================================================
   SERVER-SIDE SESSION POLLING
   Polls /appointments/{id}/call-status every 20 seconds.
   If the server says active=false, auto-ends the call on both sides.
   ================================================================ */
const CALL_STATUS_URL = "{{ route('appointments.call-status', ['id' => $appointment->id]) }}";
let statusPollInterval = null;

async function pollCallStatus() {
    if (callEnded) return; // Don't poll if call already ended
    
    try {
        const res = await fetch(CALL_STATUS_URL, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        });
        if (!res.ok) return;
        const data = await res.json();

        if (!data.active && !callEnded) {
            callEnded = true;
            clearInterval(statusPollInterval);
            toastr.warning('Call ended by the doctor or system');
            handleCallEnded();
        }
    } catch (err) {
        console.warn('Call status poll failed:', err.message);
    }
}

function startStatusPolling() {
    statusPollInterval = setInterval(pollCallStatus, 20000);
}

/* ================================================================
   PRESCRIPTION — DOCTOR SIDE
   ================================================================ */
if (IS_DOCTOR) {

    const rxSidebar = document.getElementById('rxSidebar');
    const rxToggleBtn = document.getElementById('rxToggleBtn');

    rxToggleBtn?.addEventListener('click', () => {
        rxSidebar?.classList.toggle('open');
    });

    document.getElementById('btnAddMed')?.addEventListener('click', addMedRow);

    function addMedRow(data = {}) {
        const row = document.createElement('div');
        row.className = 'med-row';
        const name = esc(data.name ?? '');
        const dosage = esc(data.dosage ?? '');
        const duration = esc(data.duration ?? '');

        row.innerHTML = `
            <input type="text" placeholder="Medicine name" value="${name}" data-field="name">
            <input type="text" placeholder="Dosage" value="${dosage}" data-field="dosage">
            <input type="text" placeholder="Duration" value="${duration}" data-field="duration">
            <button type="button" class="btn-del-med" title="Remove">
                <i class="ti ti-trash"></i>
            </button>`;
        row.querySelector('.btn-del-med').addEventListener('click', () => row.remove());
        document.getElementById('medList').appendChild(row);
    }

    if (!document.querySelector('#medList .med-row')) {
        addMedRow();
    }

    document.querySelectorAll('.btn-del-med').forEach(btn =>
        btn.addEventListener('click', () => btn.closest('.med-row').remove())
    );

    document.getElementById('btnSaveRx')?.addEventListener('click', saveRx);

    async function saveRx() {
        const btn    = document.getElementById('btnSaveRx');
        const status = document.getElementById('saveStatus');
        btn.disabled = true;
        status.textContent = 'Saving…';
        status.className   = 'save-status';

        const medicines = [];
        document.querySelectorAll('#medList .med-row').forEach(row => {
            const name = row.querySelector('[data-field="name"]').value.trim();
            if (!name) return;
            medicines.push({
                name,
                dosage:   row.querySelector('[data-field="dosage"]').value.trim(),
                duration: row.querySelector('[data-field="duration"]').value.trim(),
            });
        });

        try {
            const res = await fetch(RX_STORE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    diagnosis: document.getElementById('rxDiagnosis').value,
                    medicines: medicines,
                    notes:     document.getElementById('rxNotes').value,
                }),
            });
            const data = await res.json();
            if (!res.ok) {
                if (data.errors) {
                    const msgs = Object.values(data.errors).flat().join(', ');
                    throw new Error(msgs);
                }
                throw new Error(data.message ?? data.error ?? 'Save failed');
            }
            status.textContent = '✓ Saved';
            status.className   = 'save-status ok';
            toastr.success('Prescription saved successfully');
        } catch (err) {
            status.textContent = '✗ ' + err.message;
            status.className   = 'save-status err';
            toastr.error('Prescription save failed: ' + err.message);
        } finally {
            btn.disabled = false;
        }
    }
}

/* ================================================================
   PRESCRIPTION — PATIENT SIDE (polling) — DISABLED DURING CALL
   Patient sees prescriptions only after call ends on appointment page
   ================================================================ */
// Patient prescription fetching during call is intentionally disabled
// Patients will view prescriptions on the appointment details page after the call ends

/* ================================================================
   UTILITIES
   ================================================================ */
function esc(str) {
    return (str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Override the earlier implementation so call shutdown uses the
// elements that actually exist in this template.
function handleCallEnded() {
    const callStatus = document.getElementById('callStatus');
    const statusText = document.getElementById('statusText');
    const statusDot = document.getElementById('statusDot');

    if (callStatus) {
        callStatus.classList.add('ended');
    }

    if (statusText) {
        statusText.textContent = 'Ended';
    }

    if (statusDot) {
        statusDot.textContent = '●';
    }

    document.getElementById('btnMic').disabled = true;
    document.getElementById('btnCam').disabled = true;
    document.getElementById('btnEnd')?.setAttribute('disabled', 'disabled');

    setTimeout(() => {
        window.location.href = APPT_SHOW_URL;
    }, 1500);
}
</script>
@endpush
