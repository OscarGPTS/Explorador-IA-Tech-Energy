@extends('layouts.app')

@push('styles')
<style>
    .eia-bg { background: var(--eia-bg); }

    /* HERO (mismo lenguaje visual que el buscador de documentos) */
    .voz-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
        position: relative;
    }
    .voz-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .voz-back {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.04);
        display: inline-flex; align-items: center; justify-content: center;
        color: #E2E8F0;
        transition: all .2s ease;
    }
    .voz-back:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--eia-gold);
        color: #FFFFFF;
    }
    .eia-eyebrow {
        font-size: 11px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--eia-gold-soft);
        font-weight: 600;
    }
    .voz-action {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 16px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(255, 255, 255, 0.06);
        color: #FFFFFF;
        border-radius: 10px;
        font-size: 12.5px; font-weight: 600; letter-spacing: 0.02em;
        transition: all .2s ease; cursor: pointer;
    }
    .voz-action:hover { background: rgba(217, 119, 6, 0.15); border-color: var(--eia-gold); }

    /* Panel */
    .voz-panel {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 16px;
        box-shadow: 0 1px 2px rgba(15, 20, 25, 0.04), 0 8px 24px rgba(15, 20, 25, 0.06);
    }
    .voz-panel-head { padding: 18px 22px; border-bottom: 1px solid var(--eia-border); }
    .voz-panel-title { font-size: 14px; font-weight: 600; color: var(--eia-graphite); }
    .voz-panel-sub { font-size: 12px; color: var(--eia-mute); margin-top: 2px; }

    /* Botón de micrófono */
    .voz-mic {
        width: 116px; height: 116px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(145deg, #B91C1C 0%, #D97706 100%);
        color: #FFFFFF;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(185, 28, 28, 0.35);
        transition: transform .15s ease, box-shadow .2s ease;
    }
    .voz-mic:hover { transform: translateY(-2px); }
    .voz-mic:disabled { opacity: .55; cursor: not-allowed; transform: none; }
    .voz-mic.recording {
        background: linear-gradient(145deg, #DC2626 0%, #991B1B 100%);
        animation: voz-pulse 1.4s ease-in-out infinite;
    }
    @keyframes voz-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.45); }
        70%  { box-shadow: 0 0 0 22px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }

    .voz-status { font-size: 13px; color: var(--eia-slate); }
    .voz-timer { font-variant-numeric: tabular-nums; font-weight: 600; color: var(--eia-graphite); }

    .voz-chip {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 11px; font-weight: 600;
        padding: 4px 10px; border-radius: 999px;
        border: 1px solid var(--eia-border); color: var(--eia-slate);
        background: #F8FAFC;
    }

    .voz-seg { display: inline-flex; border: 1px solid var(--eia-border); border-radius: 10px; overflow: hidden; }
    .voz-seg label { cursor: pointer; }
    .voz-seg input { display: none; }
    .voz-seg span {
        display: inline-block; padding: 7px 16px;
        font-size: 12.5px; font-weight: 600; color: var(--eia-mute);
        transition: all .15s ease;
    }
    .voz-seg input:checked + span { background: var(--eia-graphite); color: #FFFFFF; }

    .voz-result-label { font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: var(--eia-mute); font-weight: 600; }
    .voz-transcript {
        background: #F1F5F9; border: 1px solid var(--eia-border);
        border-radius: 12px; padding: 14px 16px; color: var(--eia-graphite);
        font-size: 14px; line-height: 1.5;
    }
    .voz-answer {
        background: #FFFFFF; border: 1px solid var(--eia-border);
        border-radius: 12px; padding: 16px; color: var(--eia-slate);
        font-size: 14px; line-height: 1.6; white-space: pre-wrap;
    }
    .voz-spinner {
        width: 22px; height: 22px; border-radius: 50%;
        border: 3px solid var(--eia-border); border-top-color: var(--eia-gold);
        animation: voz-spin .7s linear infinite;
    }
    @keyframes voz-spin { to { transform: rotate(360deg); } }

    .voz-alert {
        border-radius: 12px; padding: 12px 16px; font-size: 13px;
        border: 1px solid #FCA5A5; background: #FEF2F2; color: #991B1B;
    }
    .voz-alert.ok { border-color: #86EFAC; background: #F0FDF4; color: #166534; }

    .voz-health-row { display: flex; align-items: center; justify-content: space-between; padding: 7px 0; font-size: 12.5px; }
    .voz-health-row + .voz-health-row { border-top: 1px dashed var(--eia-border); }
    .voz-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; }
    .voz-dot.on { background: #16A34A; } .voz-dot.off { background: #DC2626; }
</style>
@endpush

@section('content')
<div class="eia-bg min-h-screen">

    {{-- HERO --}}
    <section class="voz-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-5xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="/" class="voz-back" aria-label="Volver al inicio">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="eia-eyebrow">Servicio de voz · RAG</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Prueba de voz</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                        Graba una pregunta con el micrófono, envíala al servicio y recibe la respuesta en texto y voz.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <button id="btn-health" class="voz-action" type="button">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Health check
                </button>
            </div>
        </div>
    </section>

    {{-- BODY --}}
    <div class="px-4 sm:px-8 lg:px-12 py-8">
        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- GRABADOR --}}
            <section class="voz-panel lg:col-span-2">
                <div class="voz-panel-head flex items-center justify-between gap-3">
                    <div>
                        <p class="voz-panel-title">Grabar pregunta</p>
                        <p class="voz-panel-sub">El navegador captura audio <code>webm/opus</code> con el micrófono.</p>
                    </div>
                    <span class="voz-chip">
                        <span id="health-mini-dot" class="voz-dot off"></span>
                        <span id="health-mini-text">sin verificar</span>
                    </span>
                </div>

                <div class="p-6">
                    {{-- Selector de formato --}}
                    <div class="flex items-center gap-3 flex-wrap mb-6">
                        <span class="text-xs font-semibold text-slate-500">Formato de respuesta</span>
                        <div class="voz-seg">
                            <label>
                                <input type="radio" name="formato" value="ambos" checked>
                                <span>Texto + voz</span>
                            </label>
                            <label>
                                <input type="radio" name="formato" value="texto">
                                <span>Solo texto</span>
                            </label>
                        </div>
                    </div>

                    {{-- Micrófono --}}
                    <div class="flex flex-col items-center gap-4 py-4">
                        <button id="btn-mic" class="voz-mic" type="button" aria-label="Grabar">
                            <svg id="mic-icon" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/>
                            </svg>
                        </button>
                        <div class="text-center">
                            <p id="mic-status" class="voz-status">Pulsa para grabar</p>
                            <p id="mic-timer" class="voz-timer mt-1">00:00</p>
                        </div>
                    </div>

                    {{-- Reproducción del audio grabado (preview) --}}
                    <div id="preview-wrap" class="hidden mt-4">
                        <p class="voz-result-label mb-2">Audio grabado</p>
                        <audio id="preview-audio" controls class="w-full"></audio>
                    </div>

                    {{-- Mensajes --}}
                    <div id="voz-message" class="hidden mt-5"></div>
                </div>
            </section>

            {{-- RESULTADO --}}
            <section class="voz-panel lg:col-span-3" id="result-panel" style="display:none;">
                <div class="voz-panel-head flex items-center justify-between gap-3">
                    <div>
                        <p class="voz-panel-title">Respuesta del servicio</p>
                        <p class="voz-panel-sub" id="result-time"></p>
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <p class="voz-result-label mb-2">Pregunta transcrita</p>
                        <div id="result-transcript" class="voz-transcript"></div>
                    </div>
                    <div>
                        <p class="voz-result-label mb-2">Respuesta</p>
                        <div id="result-answer" class="voz-answer"></div>
                    </div>
                    <div id="result-audio-wrap" class="hidden">
                        <p class="voz-result-label mb-2">Respuesta en voz</p>
                        <audio id="result-audio" controls class="w-full"></audio>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

{{-- Modal de Health check --}}
<div id="health-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4" style="background: rgba(15,20,25,.55);">
    <div class="voz-panel w-full max-w-md">
        <div class="voz-panel-head flex items-center justify-between">
            <p class="voz-panel-title">Estado del servicio de voz</p>
            <button id="health-close" class="text-slate-400 hover:text-slate-700" type="button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="health-body" class="p-6">
            <div class="flex items-center justify-center py-6"><div class="voz-spinner"></div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const CSRF = '{{ csrf_token() }}';
    const URL_CONSULTA = '{{ route('voz.consulta', [], false) }}';
    const URL_HEALTH   = '{{ route('voz.health', [], false) }}';

    const btnMic   = document.getElementById('btn-mic');
    const micIcon  = document.getElementById('mic-icon');
    const micStatus= document.getElementById('mic-status');
    const micTimer = document.getElementById('mic-timer');
    const message  = document.getElementById('voz-message');

    const previewWrap  = document.getElementById('preview-wrap');
    const previewAudio = document.getElementById('preview-audio');

    const resultPanel    = document.getElementById('result-panel');
    const resultTime     = document.getElementById('result-time');
    const resultTranscript = document.getElementById('result-transcript');
    const resultAnswer   = document.getElementById('result-answer');
    const resultAudioWrap= document.getElementById('result-audio-wrap');
    const resultAudio    = document.getElementById('result-audio');

    let mediaRecorder = null;
    let chunks = [];
    let stream = null;
    let timerInt = null;
    let seconds = 0;
    let recording = false;

    function showMessage(text, ok) {
        message.className = 'voz-alert' + (ok ? ' ok' : '');
        message.textContent = text;
        message.classList.remove('hidden');
    }
    function clearMessage() {
        message.classList.add('hidden');
        message.textContent = '';
    }

    function fmt(s) {
        const m = Math.floor(s / 60).toString().padStart(2, '0');
        const r = (s % 60).toString().padStart(2, '0');
        return `${m}:${r}`;
    }
    function startTimer() {
        seconds = 0; micTimer.textContent = '00:00';
        timerInt = setInterval(() => {
            seconds++;
            micTimer.textContent = fmt(seconds);
            // Corte de seguridad: el servicio recomienda <= 60s
            if (seconds >= 60) stopRecording();
        }, 1000);
    }
    function stopTimer() { clearInterval(timerInt); timerInt = null; }

    async function startRecording() {
        clearMessage();
        try {
            stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        } catch (e) {
            showMessage('No se pudo acceder al micrófono: ' + e.message, false);
            return;
        }
        const mime = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : '';
        mediaRecorder = mime ? new MediaRecorder(stream, { mimeType: mime }) : new MediaRecorder(stream);
        chunks = [];
        mediaRecorder.ondataavailable = (e) => { if (e.data.size > 0) chunks.push(e.data); };
        mediaRecorder.onstop = onRecordingStop;
        mediaRecorder.start();

        recording = true;
        btnMic.classList.add('recording');
        micStatus.textContent = 'Grabando… pulsa para detener';
        startTimer();
    }

    function stopRecording() {
        if (!mediaRecorder || mediaRecorder.state === 'inactive') return;
        mediaRecorder.stop();
        stopTimer();
        recording = false;
        btnMic.classList.remove('recording');
    }

    async function onRecordingStop() {
        if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
        const blob = new Blob(chunks, { type: 'audio/webm' });

        // Preview del audio grabado
        previewAudio.src = window.URL.createObjectURL(blob);
        previewWrap.classList.remove('hidden');

        if (blob.size === 0) {
            showMessage('No se capturó audio. Intenta de nuevo.', false);
            micStatus.textContent = 'Pulsa para grabar';
            return;
        }
        await enviar(blob);
    }

    async function enviar(blob) {
        const formato = document.querySelector('input[name="formato"]:checked').value;
        const form = new FormData();
        form.append('file', blob, 'pregunta.webm');
        form.append('formato_respuesta', formato);

        btnMic.disabled = true;
        micStatus.innerHTML = '<span class="inline-flex items-center gap-2"><span class="voz-spinner" style="width:16px;height:16px;border-width:2px;"></span> Procesando…</span>';
        clearMessage();

        try {
            const resp = await fetch(URL_CONSULTA, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: form
            });
            const result = await resp.json();

            if (result.success && result.data) {
                renderResult(result.data);
            } else {
                showMessage(result.error || result.message || 'Error en la consulta.', false);
            }
        } catch (e) {
            showMessage('Error de conexión: ' + e.message, false);
        } finally {
            btnMic.disabled = false;
            micStatus.textContent = 'Pulsa para grabar';
            micTimer.textContent = '00:00';
        }
    }

    function renderResult(data) {
        resultPanel.style.display = 'block';
        resultTranscript.textContent = data.pregunta_transcrita || '(sin transcripción)';
        resultAnswer.textContent = data.respuesta || '(sin respuesta)';
        resultTime.textContent = (typeof data.tiempo_respuesta === 'number')
            ? `Tiempo total: ${data.tiempo_respuesta.toFixed(1)} s`
            : '';

        if (data.audio_base64) {
            const bytes = Uint8Array.from(atob(data.audio_base64), c => c.charCodeAt(0));
            const url = window.URL.createObjectURL(new Blob([bytes], { type: 'audio/wav' }));
            resultAudio.src = url;
            resultAudioWrap.classList.remove('hidden');
            resultAudio.play().catch(() => {}); // autoplay puede bloquearse; el usuario tiene los controles
        } else {
            resultAudioWrap.classList.add('hidden');
            resultAudio.removeAttribute('src');
        }
        resultPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    btnMic.addEventListener('click', () => {
        if (recording) stopRecording();
        else startRecording();
    });

    /* ---------- Health check ---------- */
    const healthModal = document.getElementById('health-modal');
    const healthBody  = document.getElementById('health-body');
    const healthMiniDot  = document.getElementById('health-mini-dot');
    const healthMiniText = document.getElementById('health-mini-text');

    document.getElementById('btn-health').addEventListener('click', loadHealth);
    document.getElementById('health-close').addEventListener('click', () => healthModal.classList.add('hidden'));
    healthModal.addEventListener('click', (e) => { if (e.target === healthModal) healthModal.classList.add('hidden'); });

    function boolRow(label, val) {
        const on = val === true;
        return `<div class="voz-health-row">
            <span class="text-slate-600">${label}</span>
            <span class="inline-flex items-center gap-2">
                <span class="voz-dot ${on ? 'on' : 'off'}"></span>
                <span class="font-semibold ${on ? 'text-green-700' : 'text-red-600'}">${val === undefined ? 'n/d' : (on ? 'sí' : 'no')}</span>
            </span>
        </div>`;
    }
    function textRow(label, val) {
        return `<div class="voz-health-row">
            <span class="text-slate-600">${label}</span>
            <span class="font-semibold text-slate-800">${val ?? 'n/d'}</span>
        </div>`;
    }

    async function loadHealth() {
        healthModal.classList.remove('hidden');
        healthBody.innerHTML = '<div class="flex items-center justify-center py-6"><div class="voz-spinner"></div></div>';
        try {
            const resp = await fetch(URL_HEALTH, { headers: { 'Accept': 'application/json' } });
            const result = await resp.json();
            if (!result.success) {
                healthBody.innerHTML = `<div class="voz-alert">${result.error || 'No se pudo consultar el estado.'}</div>`;
                setMini(false);
                return;
            }
            const d = result.data || {};
            setMini(d.voice_enabled === true);
            healthBody.innerHTML = `
                <div class="voz-alert ${d.voice_enabled ? 'ok' : ''} mb-4">
                    ${d.voice_enabled ? 'El servicio de voz está habilitado.' : 'El servicio de voz está deshabilitado (responderá 503).'}
                </div>
                ${boolRow('Voice enabled', d.voice_enabled)}
                ${textRow('STT provider', d.stt_provider)}
                ${textRow('TTS provider', d.tts_provider)}
                ${textRow('Whisper model', d.whisper_model)}
                ${textRow('Backend RAG', d.backend_rag)}
                ${boolRow('ffmpeg', d.ffmpeg_disponible)}
                ${boolRow('faster-whisper', d.faster_whisper_disponible)}
                ${boolRow('piper', d.piper_disponible)}
                ${boolRow('voz piper existe', d.voz_piper_existe)}
                ${boolRow('openai', d.openai_disponible)}
            `;
        } catch (e) {
            healthBody.innerHTML = `<div class="voz-alert">Error de conexión: ${e.message}</div>`;
            setMini(false);
        }
    }
    function setMini(ok) {
        healthMiniDot.className = 'voz-dot ' + (ok ? 'on' : 'off');
        healthMiniText.textContent = ok ? 'servicio listo' : 'no disponible';
    }

    // Verificación silenciosa al cargar para el chip de estado
    fetch(URL_HEALTH, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(r => setMini(r.success && r.data && r.data.voice_enabled === true))
        .catch(() => setMini(false));
})();
</script>
@endpush
