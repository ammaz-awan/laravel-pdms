@extends('layouts.layout')

@section('title', 'Video Consultation')

@push('styles')
<style>
/* ================================================================
   VIDEO CALL PAGE — Medical Blue/White Theme
   ================================================================ */

:root {
    --vc-primary:     #1a6fc4;
    --vc-primary-dk:  #145aa0;
    --vc-accent:      #0dcaf0;
    --vc-bg:          #f0f4f9;
    --vc-card:        #ffffff;
    --vc-border:      #d0dce8;
    --vc-text:        #1e2d3d;
    --vc-muted:       #6c8093;
    --vc-danger:      #dc3545;
    --vc-success:     #198754;
}

body { background: var(--vc-bg); }

/* ---------- top header bar ---------- */
.vc-header {
    background: var(--vc-primary);
    color: #fff;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,.25);
}
.vc-header .appt-meta { font-size: .85rem; opacity: .88; }
.vc-header .timer-badge {
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 20px;
    padding: 4px 14px;
    font-weight: 700;
    font-size: 1rem;
    letter-spacing: 1px;
}
.vc-header .status-pill {
    border-radius: 20px;
    padding: 3px 12px;
    font-size: .8rem;
    font-weight: 600;
}
.status-active  { background: #198754; color: #fff; }
.status-waiting { background: #ffc107; color: #000; }
.status-ended   { background: #6c757d; color: #fff; }

/* ---------- main 2-column grid ---------- */
.vc-body {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 16px;
    padding: 16px;
    height: calc(100vh - 60px);
    max-width: 1400px;
    margin: 0 auto;
    box-sizing: border-box;
}

@media (max-width: 1024px) {
    .vc-body {
        grid-template-columns: 1fr;
        height: auto;
    }
}

/* ---------- left column: video area ---------- */
.vc-left {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.video-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    flex: 1;
}

@media (max-width: 600px) {
    .video-grid { grid-template-columns: 1fr; }
}

.video-box {
    background: #1e2d3d;
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    min-height: 240px;
    box-shadow: 0 4px 16px rgba(0,0,0,.25);
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-box video,
.video-box div[id] {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover;
    position: absolute;
    top: 0; left: 0;
}

.video-label {
    position: absolute;
    bottom: 10px;
    left: 12px;
    background: rgba(0,0,0,.55);
    color: #fff;
    font-size: .78rem;
    padding: 3px 10px;
    border-radius: 12px;
    z-index: 10;
}

.video-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #8aa4bb;
    font-size: .9rem;
    gap: 8px;
}
.video-placeholder i { font-size: 2.4rem; }

/* ---------- control bar ---------- */
.control-bar {
    background: var(--vc-card);
    border-radius: 14px;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
}

.ctrl-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s;
}
.ctrl-btn:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(0,0,0,.2); }

.btn-mic    { background: #e8f0fe; color: var(--vc-primary); }
.btn-mic.muted { background: #fee2e2; color: var(--vc-danger); }
.btn-cam    { background: #e8f0fe; color: var(--vc-primary); }
.btn-cam.off { background: #fee2e2; color: var(--vc-danger); }
.btn-end    { background: var(--vc-danger); color: #fff; width: 56px; height: 56px; font-size: 1.3rem; }

/* ---------- right column: prescription ---------- */
.vc-right {
    background: var(--vc-card);
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid var(--vc-border);
}

.rx-header {
    background: var(--vc-primary);
    color: #fff;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.rx-header i { font-size: 1.2rem; }
.rx-header h5 { margin: 0; font-size: 1rem; font-weight: 700; }

.rx-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.rx-field label {
    font-size: .78rem;
    font-weight: 700;
    color: var(--vc-muted);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 4px;
    display: block;
}

.rx-field textarea,
.rx-field input {
    width: 100%;
    border: 1px solid var(--vc-border);
    border-radius: 8px;
    padding: 8px 10px;
    font-size: .88rem;
    resize: vertical;
    transition: border-color .2s;
    color: var(--vc-text);
}
.rx-field textarea:focus,
.rx-field input:focus {
    outline: none;
    border-color: var(--vc-primary);
    box-shadow: 0 0 0 3px rgba(26,111,196,.12);
}

/* Medicines dynamic list */
.med-list { display: flex; flex-direction: column; gap: 8px; }

.med-row {
    display: grid;
    grid-template-columns: 1fr 80px 80px 30px;
    gap: 6px;
    align-items: center;
}

.med-row input {
    border: 1px solid var(--vc-border);
    border-radius: 6px;
    padding: 6px 8px;
    font-size: .82rem;
}
.med-row input:focus {
    outline: none;
    border-color: var(--vc-primary);
}

.btn-add-med {
    background: none;
    border: 1px dashed var(--vc-primary);
    color: var(--vc-primary);
    border-radius: 8px;
    padding: 6px 12px;
    font-size: .82rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    width: 100%;
    justify-content: center;
    transition: background .15s;
}
.btn-add-med:hover { background: #e8f0fe; }

.btn-del-med {
    background: none;
    border: none;
    color: var(--vc-danger);
    cursor: pointer;
    font-size: 1rem;
    padding: 0;
    line-height: 1;
}

/* readonly view for patients */
.rx-readonly .rx-section {
    padding: 10px 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid var(--vc-border);
}
.rx-readonly .rx-section h6 {
    font-size: .75rem;
    font-weight: 700;
    color: var(--vc-muted);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 6px;
}
.rx-readonly .rx-section p { margin: 0; font-size: .88rem; color: var(--vc-text); }

.med-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.med-table th { background: #e8f0fe; color: var(--vc-primary); padding: 5px 8px; text-align: left; }
.med-table td { padding: 5px 8px; border-bottom: 1px solid var(--vc-border); }

/* save button area */
.rx-footer {
    padding: 12px 16px;
    border-top: 1px solid var(--vc-border);
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-save-rx {
    background: var(--vc-primary);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 9px 22px;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
    display: flex;
    align-items: center;
    gap: 8px;
}
.btn-save-rx:hover { background: var(--vc-primary-dk); }
.btn-save-rx:disabled { opacity: .6; cursor: not-allowed; }

.save-status {
    font-size: .8rem;
    color: var(--vc-muted);
}
.save-status.ok  { color: var(--vc-success); }
.save-status.err { color: var(--vc-danger); }

/* call-ended overlay */
.call-ended-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.75);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.call-ended-overlay.show { display: flex; }
.call-ended-card {
    background: #fff;
    border-radius: 16px;
    padding: 40px 50px;
    text-align: center;
    box-shadow: 0 8px 40px rgba(0,0,0,.3);
}
.call-ended-card i { font-size: 3rem; color: var(--vc-success); }
.call-ended-card h3 { margin: 12px 0 6px; }
.call-ended-card p { color: var(--vc-muted); font-size: .9rem; }
</style>
@endpush

@section('content')
{{-- Call-ended overlay --}}
<div class="call-ended-overlay" id="callEndedOverlay">
    <div class="call-ended-card">
        <i class="ti ti-circle-check"></i>
        <h3>Consultation Ended</h3>
        <p>The video call has been completed.</p>
        <a href="{{ route('appointments.show', $appointment) }}"
           class="btn btn-primary mt-3">View Appointment</a>
    </div>
</div>

{{-- Header bar --}}
<div class="vc-header">
    <div>
        <div class="fw-bold fs-5">
            <i class="ti ti-video me-1"></i> Video Consultation
        </div>
        <div class="appt-meta">
            Dr. {{ $appointment->doctor->user->name }} &nbsp;·&nbsp;
            {{ $appointment->patient->user->name }} &nbsp;·&nbsp;
            {{ $appointment->appointment_date->format('M d, Y') }}
            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <span class="timer-badge" id="callTimer">00:00</span>
        <span class="status-pill status-active" id="callStatusPill">● Live</span>
    </div>
</div>

{{-- Main body --}}
<div class="vc-body">

    {{-- ===== Left: video + controls ===== --}}
    <div class="vc-left">
        <div class="video-grid">
            {{-- Local video --}}
            <div class="video-box" id="local-video">
                <div class="video-placeholder">
                    <i class="ti ti-user-circle"></i>
                    <span>Connecting camera…</span>
                </div>
                <span class="video-label">
                    {{ $callData['is_doctor'] ? 'You (Doctor)' : 'You (Patient)' }}
                </span>
            </div>

            {{-- Remote video --}}
            <div class="video-box" id="remote-video">
                <div class="video-placeholder">
                    <i class="ti ti-user-circle"></i>
                    <span>Waiting for
                        {{ $callData['is_doctor'] ? 'Patient' : 'Doctor' }}…
                    </span>
                </div>
                <span class="video-label">
                    {{ $callData['is_doctor'] ? $appointment->patient->user->name : 'Dr. '.$appointment->doctor->user->name }}
                </span>
            </div>
        </div>

        {{-- Control bar --}}
        <div class="control-bar">
            <button class="ctrl-btn btn-mic" id="btnMic" title="Toggle Mic">
                <i class="ti ti-microphone" id="micIcon"></i>
            </button>
            <button class="ctrl-btn btn-cam" id="btnCam" title="Toggle Camera">
                <i class="ti ti-video" id="camIcon"></i>
            </button>

            @if($callData['is_doctor'])
            <button class="ctrl-btn btn-end" id="btnEnd" title="End Call">
                <i class="ti ti-phone-off"></i>
            </button>
            @endif
        </div>
    </div>

    {{-- ===== Right: prescription panel ===== --}}
    <div class="vc-right">
        <div class="rx-header">
            <i class="ti ti-notes-medical"></i>
            <h5>
                @if($callData['is_doctor'])
                    Prescription &nbsp;<small style="font-weight:400;opacity:.8">(edit)</small>
                @else
                    Prescription &nbsp;<small style="font-weight:400;opacity:.8">(read-only)</small>
                @endif
            </h5>
        </div>

        {{-- DOCTOR EDIT FORM --}}
        @if($callData['is_doctor'])
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
                        <input type="text" placeholder="Dosage"        value="{{ $med['dosage'] ?? '' }}" data-field="dosage">
                        <input type="text" placeholder="Duration"      value="{{ $med['duration'] ?? '' }}" data-field="duration">
                        <button type="button" class="btn-del-med" title="Remove">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                    @empty
                    {{-- empty; JS will add rows --}}
                    @endforelse
                </div>
                <button type="button" class="btn-add-med mt-2" id="btnAddMed">
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
                <i class="ti ti-device-floppy"></i> Save Prescription
            </button>
            <span class="save-status" id="saveStatus"></span>
        </div>

        {{-- PATIENT READ-ONLY VIEW --}}
        @else
        <div class="rx-body rx-readonly" id="patientRxView">
            <div class="rx-section">
                <h6>Diagnosis</h6>
                <p id="pDiagnosis">—</p>
            </div>
            <div class="rx-section">
                <h6>Medicines</h6>
                <div id="pMedicines">
                    <table class="med-table">
                        <thead>
                            <tr><th>Medicine</th><th>Dosage</th><th>Duration</th></tr>
                        </thead>
                        <tbody id="pMedTbody">
                            <tr><td colspan="3" style="color:#aaa;text-align:center">Loading…</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="rx-section">
                <h6>Notes</h6>
                <p id="pNotes">—</p>
            </div>
        </div>
        <div class="rx-footer" style="justify-content:center">
            <small class="text-muted">
                <i class="ti ti-refresh me-1"></i>
                Auto-refreshes every 10 seconds
            </small>
        </div>
        @endif
    </div>

</div>{{-- /.vc-body --}}
@endsection

@push('scripts')
{{-- Agora RTC SDK --}}
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>

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

// DEBUG: Log config values to console
console.log('=== AGORA CONFIG DEBUG ===');
console.log('App ID:', AGORA_APP_ID);
console.log('App ID length:', AGORA_APP_ID ? AGORA_APP_ID.length : 'N/A');
console.log('Channel:', AGORA_CHANNEL);
console.log('UID:', AGORA_UID);
console.log('Token present:', !!AGORA_TOKEN);
console.log('Token prefix (first 35 chars):', AGORA_TOKEN ? AGORA_TOKEN.substring(0, 35) : 'NULL');
console.log('App ID embedded in token:', AGORA_TOKEN ? AGORA_TOKEN.substring(3, 35) : 'NULL');
console.log('App ID matches token:', AGORA_TOKEN ? (AGORA_APP_ID === AGORA_TOKEN.substring(3, 35) ? '✓ MATCH' : '✗ MISMATCH!') : 'N/A');
console.log('Token expires at:', new Date(EXPIRES_AT * 1000).toISOString());
console.log('Token expired?:', EXPIRES_AT * 1000 < Date.now() ? '✗ YES - TOKEN IS EXPIRED!' : '✓ No');
console.log('==========================');

/* ================================================================
   TIMER
   ================================================================ */
let startTime = Date.now();
const timerEl = document.getElementById('callTimer');

function updateTimer() {
    const remaining = Math.max(0, EXPIRES_AT * 1000 - Date.now());
    const elapsed   = Date.now() - startTime;
    const m = String(Math.floor(elapsed / 60000)).padStart(2, '0');
    const s = String(Math.floor((elapsed % 60000) / 1000)).padStart(2, '0');
    timerEl.textContent = m + ':' + s;

    if (remaining <= 0) {
        clearInterval(timerInterval);
        handleCallEnded();
    }
}
const timerInterval = setInterval(updateTimer, 1000);

/* ================================================================
   AGORA RTC
   ================================================================ */
const client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });
let localTracks = { audio: null, video: null };
let micMuted = false;
let camOff   = false;

async function initAgora() {
    // ── ISOLATION TEST ──────────────────────────────────────────────
    // To isolate whether the issue is the App ID or the token:
    // 1. Set ISOLATION_TEST = true  → joins with null token ("App ID only" mode)
    //    - If this SUCCEEDS → token format is wrong (App ID is valid)
    //    - If this FAILS with same error → App ID is invalid/not found in Agora Console
    // 2. Set ISOLATION_TEST = false → normal mode with token (production)
    // NOTE: Your Agora project must have App Certificate DISABLED for null-token join to work.
    const ISOLATION_TEST = false;
    if (ISOLATION_TEST) {
        console.warn('⚠️  ISOLATION TEST MODE: joining without token to test App ID validity');
        try {
            await client.join(AGORA_APP_ID, 'test_isolation_123', null, null);
            console.log('✅ ISOLATION TEST PASSED: App ID is valid! The issue is the token format.');
            await client.leave();
        } catch (err) {
            console.error('❌ ISOLATION TEST FAILED: App ID is invalid or not found!', err.message);
            console.error('→ Action required: Log in to https://console.agora.io and verify project with App ID:', AGORA_APP_ID);
        }
        return;
    }
    // ── END ISOLATION TEST ──────────────────────────────────────────

    // Validate required config before joining
    if (!AGORA_APP_ID) {
        console.error('❌ FATAL: Agora App ID is missing or empty!', {
            app_id: AGORA_APP_ID,
            channel: AGORA_CHANNEL,
            uid: AGORA_UID
        });
        alert('ERROR: Agora App ID is not configured. Please check your .env file.');
        return;
    }

    if (!AGORA_TOKEN) {
        console.error('❌ FATAL: Agora Token is missing or empty!');
        alert('ERROR: Agora authentication token is missing.');
        return;
    }

    try {
        await client.join(AGORA_APP_ID, AGORA_CHANNEL, AGORA_TOKEN, AGORA_UID);

        // Create local tracks
        [localTracks.audio, localTracks.video] =
            await AgoraRTC.createMicrophoneAndCameraTracks();

        // Play local video in #local-video
        const localBox = document.getElementById('local-video');
        // Remove placeholder
        localBox.querySelector('.video-placeholder')?.remove();
        localTracks.video.play('local-video');

        // Publish
        await client.publish([localTracks.audio, localTracks.video]);

        // Start server-side session polling (auto-ends call if doctor ends or time expires)
        startStatusPolling();

        // Handle remote users already in channel
        client.remoteUsers.forEach(handleRemoteUser);

        // Subscribe to new remote users
        client.on('user-published', async (user, mediaType) => {
            await client.subscribe(user, mediaType);
            if (mediaType === 'video') {
                const remoteBox = document.getElementById('remote-video');
                remoteBox.querySelector('.video-placeholder')?.remove();
                user.videoTrack.play('remote-video');
        }
        if (mediaType === 'audio') {
            user.audioTrack.play();
        }
    });

    client.on('user-unpublished', (user, mediaType) => {
        if (mediaType === 'video') {
            const remoteBox = document.getElementById('remote-video');
            if (!remoteBox.querySelector('.video-placeholder')) {
                remoteBox.innerHTML = `
                    <div class="video-placeholder">
                        <i class="ti ti-user-circle"></i>
                        <span>Camera paused</span>
                    </div>
                    <span class="video-label">${remoteBox.querySelector('.video-label')?.textContent ?? ''}</span>`;
            }
        }
    });

    client.on('user-left', () => {
        document.getElementById('remote-video').querySelector('.video-placeholder') ??
            (document.getElementById('remote-video').innerHTML = `
                <div class="video-placeholder">
                    <i class="ti ti-user-circle"></i>
                    <span>Participant left</span>
                </div>`);
    });
    } catch (error) {
        console.error('❌ Agora initialization failed:', error);
        const errorMsg = error?.message || String(error);
        showAlert('Video call connection failed: ' + errorMsg, 'danger');
        throw error; // Re-throw to be caught by the .catch() handler
    }
}

async function handleRemoteUser(user) {
    await client.subscribe(user, 'video');
    await client.subscribe(user, 'audio');
    const remoteBox = document.getElementById('remote-video');
    remoteBox.querySelector('.video-placeholder')?.remove();
    user.videoTrack?.play('remote-video');
    user.audioTrack?.play();
}

initAgora().catch(err => {
    console.error('❌ Agora init error:', err);
    console.log('Failed configuration:', {
        app_id: AGORA_APP_ID,
        channel: AGORA_CHANNEL,
        uid: AGORA_UID,
        has_token: !!AGORA_TOKEN,
    });
    showAlert('Could not connect to video call: ' + err.message, 'danger');
});

/* ================================================================
   CONTROL BUTTONS
   ================================================================ */

// Mute / unmute mic
document.getElementById('btnMic').addEventListener('click', async () => {
    micMuted = !micMuted;
    await localTracks.audio?.setMuted(micMuted);
    const btn  = document.getElementById('btnMic');
    const icon = document.getElementById('micIcon');
    btn.classList.toggle('muted', micMuted);
    icon.className = micMuted ? 'ti ti-microphone-off' : 'ti ti-microphone';
});

// Toggle camera
document.getElementById('btnCam').addEventListener('click', async () => {
    camOff = !camOff;
    await localTracks.video?.setMuted(camOff);
    const btn  = document.getElementById('btnCam');
    const icon = document.getElementById('camIcon');
    btn.classList.toggle('off', camOff);
    icon.className = camOff ? 'ti ti-video-off' : 'ti ti-video';
});

// End call (doctor only)
if (IS_DOCTOR) {
    document.getElementById('btnEnd')?.addEventListener('click', async () => {
        if (!confirm('End this consultation?')) return;
        await leaveCall();
        await fetch(END_CALL_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
        });
        handleCallEnded();
    });
}

async function leaveCall() {
    clearInterval(timerInterval);
    localTracks.audio?.close();
    localTracks.video?.close();
    await client.leave();
}

function handleCallEnded() {
    document.getElementById('callStatusPill').className = 'status-pill status-ended';
    document.getElementById('callStatusPill').textContent = '● Ended';
    document.getElementById('callEndedOverlay').classList.add('show');
    leaveCall().catch(() => {});
}

// Auto end at token expiry (client-side safety net)
const msUntilExpiry = EXPIRES_AT * 1000 - Date.now();
if (msUntilExpiry > 0) {
    setTimeout(() => handleCallEnded(), msUntilExpiry);
}

/* ================================================================
   SERVER-SIDE SESSION POLLING
   Polls /appointments/{id}/call-status every 20 seconds.
   If the server says active=false (doctor ended call, admin cancelled,
   or 30-min window expired), auto-ends the call on both sides.
   ================================================================ */
const CALL_STATUS_URL = "{{ route('appointments.call-status', ['id' => $appointment->id]) }}";
let statusPollInterval = null;

async function pollCallStatus() {
    try {
        const res  = await fetch(CALL_STATUS_URL, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        });
        if (!res.ok) return; // network hiccup – stay connected
        const data = await res.json();

        if (!data.active) {
            clearInterval(statusPollInterval);
            handleCallEnded();
        }
    } catch (_) { /* silent – don't disconnect on transient error */ }
}

// Start polling once Agora has joined (set inside initAgora success path)
function startStatusPolling() {
    statusPollInterval = setInterval(pollCallStatus, 20_000);
}

// Patch leaveCall to also stop polling
const _origLeaveCall = leaveCall;
async function leaveCall() {
    clearInterval(statusPollInterval);
    return _origLeaveCall();
}

/* ================================================================
   PRESCRIPTION — DOCTOR SIDE
   ================================================================ */
if (IS_DOCTOR) {

    // Add medicine row
    document.getElementById('btnAddMed')?.addEventListener('click', addMedRow);

    function addMedRow(data = {}) {
        const row = document.createElement('div');
        row.className = 'med-row';
        row.innerHTML = `
            <input type="text" placeholder="Medicine name" value="${data.name    ?? ''}" data-field="name">
            <input type="text" placeholder="Dosage"        value="${data.dosage  ?? ''}" data-field="dosage">
            <input type="text" placeholder="Duration"      value="${data.duration ?? ''}" data-field="duration">
            <button type="button" class="btn-del-med" title="Remove">
                <i class="ti ti-trash"></i>
            </button>`;
        row.querySelector('.btn-del-med').addEventListener('click', () => row.remove());
        document.getElementById('medList').appendChild(row);
    }

    // Wire up existing delete buttons
    document.querySelectorAll('.btn-del-med').forEach(btn =>
        btn.addEventListener('click', () => btn.closest('.med-row').remove())
    );

    // Save prescription
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
            if (!name) return; // skip rows with no medicine name
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
                    'Accept':       'application/json',
                },
                body: JSON.stringify({
                    diagnosis: document.getElementById('rxDiagnosis').value,
                    medicines: medicines,
                    notes:     document.getElementById('rxNotes').value,
                }),
            });
            const data = await res.json();
            if (!res.ok) {
                // Laravel validation errors come as data.errors (object), other errors as data.error (string)
                if (data.errors) {
                    const msgs = Object.values(data.errors).flat().join(' ');
                    throw new Error(msgs);
                }
                throw new Error(data.message ?? data.error ?? 'Save failed');
            }
            status.textContent = '✓ Saved';
            status.className   = 'save-status ok';
        } catch (err) {
            status.textContent = '✗ ' + err.message;
            status.className   = 'save-status err';
        } finally {
            btn.disabled = false;
        }
    }
}

/* ================================================================
   PRESCRIPTION — PATIENT SIDE (polling)
   ================================================================ */
if (!IS_DOCTOR) {

    async function fetchRx() {
        try {
            const res  = await fetch(RX_FETCH_URL, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
            });
            const data = await res.json();
            if (!res.ok || !data.prescription) return;

            const rx = data.prescription;
            document.getElementById('pDiagnosis').textContent = rx.diagnosis || '—';
            document.getElementById('pNotes').textContent     = rx.notes     || '—';

            const tbody = document.getElementById('pMedTbody');
            const meds  = rx.medicines ?? [];
            if (meds.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" style="color:#aaa;text-align:center">No medicines added yet</td></tr>';
            } else {
                tbody.innerHTML = meds.map(m =>
                    `<tr>
                        <td>${esc(m.name)}</td>
                        <td>${esc(m.dosage)}</td>
                        <td>${esc(m.duration)}</td>
                    </tr>`
                ).join('');
            }
        } catch (e) { /* silent */ }
    }

    fetchRx();
    setInterval(fetchRx, 10000);   // poll every 10 s
}

/* ================================================================
   UTILITIES
   ================================================================ */
function esc(str) {
    return (str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function showAlert(msg, type = 'danger') {
    const el = document.createElement('div');
    el.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
    el.style.zIndex = 99999;
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 6000);
}
</script>
@endpush
