<div>
    <style>
        :root {
            --eia-black: #0F1419;
            --eia-graphite: #1F2937;
            --eia-slate: #475569;
            --eia-mute: #64748B;
            --eia-border: #E5E7EB;
            --eia-surface: #FFFFFF;
            --eia-bg: #F8FAFC;
            --eia-red: #B91C1C;
            --eia-gold: #D97706;
            --eia-gold-soft: #FBBF24;
        }

        .chat-shell {
            background: var(--eia-bg);
            min-height: calc(100vh - 60px);
            display: flex;
            flex-direction: column;
        }

        /* Avatar EVIA en header */
        /* Avatar GPT — robot 3D dentro del badge circular */
        .gpt-avatar-badge {
            border-radius: 50%;
            background: #F3F4F6;
            border: none;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
        }
        .evia-avatar-wrap {
            position: relative;
        }
        .evia-avatar-wrap::after {
            content: '';
            position: absolute;
            bottom: -1px; right: 0;
            width: 9px; height: 9px;
            background: #10B981;
            border: 2px solid #0F1419;
            border-radius: 50%;
            z-index: 2;
        }
        .avatar-profile-btn {
            padding: 6px 10px;
            font-size: 14px;
            line-height: 1;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            cursor: pointer;
            transition: all .2s ease;
            color: #FFFFFF;
        }
        .avatar-profile-btn:hover { background: rgba(255, 255, 255, 0.15); border-color: var(--eia-gold); }
        .avatar-profile-btn.active {
            background: rgba(217, 119, 6, 0.2);
            border-color: var(--eia-gold);
        }
        .evia-headline {
            display: inline-flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap;
        }
        .evia-headline-name {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: #FFFFFF;
            line-height: 1.1;
        }
        .evia-headline-sub {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--eia-gold-soft);
            letter-spacing: 0.03em;
        }

        /* HEADER */
        .chat-hero {
            background:
                radial-gradient(900px 240px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
                radial-gradient(700px 220px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
                linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
            color: #F8FAFC;
            border-bottom: 1px solid var(--eia-graphite);
            position: relative;
            padding: 18px 28px;
        }
        .chat-hero::after {
            content: '';
            position: absolute;
            left: 0; right: 0; bottom: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
            opacity: 0.85;
        }
        .chat-back {
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.04);
            display: inline-flex; align-items: center; justify-content: center;
            color: #E2E8F0;
            transition: all .2s ease;
        }
        .chat-back:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--eia-gold);
            color: #FFFFFF;
        }
        .chat-eyebrow {
            font-size: 10px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--eia-gold-soft);
            font-weight: 600;
        }
        .chat-clear-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.06);
            color: #FFFFFF;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 600;
            transition: all .2s ease;
        }
        .chat-clear-btn:hover {
            background: rgba(185, 28, 28, 0.18);
            border-color: var(--eia-red);
        }

        /* Indicador de agente */
        .agent-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.04);
            border-radius: 10px;
            font-size: 12px;
            color: #FFFFFF;
        }
        .agent-chip .icon { font-size: 14px; }
        .agent-chip strong { font-weight: 700; }

        .agent-change-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.04);
            color: #FFFFFF;
            border-radius: 10px;
            font-size: 11.5px;
            font-weight: 600;
            transition: all .2s ease;
        }
        .agent-change-btn:hover {
            background: rgba(217, 119, 6, 0.15);
            border-color: var(--eia-gold);
        }

        /* Selector de agente */
        .agent-selector {
            background: var(--eia-surface);
            border: 1px solid var(--eia-border);
            border-radius: 12px;
            padding: 18px 20px;
            margin-top: 16px;
        }
        .agent-selector h3 {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--eia-black);
        }
        .agent-selector h4 {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--eia-mute);
        }
        .agent-tile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border: 1px solid var(--eia-border);
            border-radius: 10px;
            background: #FFFFFF;
            color: var(--eia-black);
            transition: all .2s ease;
            text-align: left;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .agent-tile::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--eia-black);
            opacity: 0;
            transition: opacity .2s ease;
        }
        .agent-tile:hover {
            background: #F8FAFC;
            border-color: #94A3B8;
        }
        .agent-tile:hover::before { opacity: 1; }
        .agent-tile.active {
            background: #FFFBEB;
            border-color: var(--eia-gold);
        }
        .agent-tile.active::before {
            opacity: 1;
            background: var(--eia-gold);
        }
        .agent-tile.user-config:hover::before { background: var(--eia-red); }
        .agent-tile.user-config.active {
            background: #FEF2F2;
            border-color: var(--eia-red);
        }
        .agent-tile.user-config.active::before { background: var(--eia-red); }

        /* Messages container */
        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 28px;
            background: #FFFFFF;
        }
        .messages-area::-webkit-scrollbar { width: 8px; }
        .messages-area::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
        .messages-area::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* Sender label */
        .sender-label {
            font-weight: 700;
            font-size: 10px;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sender-label-user { color: var(--eia-red); justify-content: flex-end; }
        .sender-label-agent { color: var(--eia-mute); }
        .sender-dot { width: 6px; height: 6px; border-radius: 50%; }
        .sender-dot.user { background: var(--eia-red); }
        .sender-dot.agent { background: var(--eia-gold); }
        .evia-avatar-sm {
            width: 22px; height: 22px;
            border-radius: 50%;
            object-fit: cover;
            background: #F3F4F6;
            border: 1.5px solid var(--eia-gold);
            box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.15);
        }
        .evia-name { color: var(--eia-black); font-weight: 700; }
        .evia-name-meta { color: var(--eia-mute); font-weight: 600; margin-left: 2px; }

        /* Message bubbles */
        .message-container {
            padding: 14px 18px;
            border-radius: 14px;
            animation: slideIn 0.3s ease-out;
            transition: box-shadow .2s ease;
        }
        .message-user {
            background: var(--eia-black);
            color: #FFFFFF;
            border: 1px solid var(--eia-black);
        }
        .message-agent {
            background: #FFFFFF;
            color: var(--eia-black);
            border: 1px solid var(--eia-border);
            box-shadow: 0 1px 2px rgba(15, 20, 25, 0.04);
        }
        .message-user-container:hover .message-user {
            box-shadow: 0 14px 28px -16px rgba(15, 20, 25, 0.5);
        }
        .message-agent-container:hover .message-agent {
            box-shadow: 0 14px 28px -16px rgba(15, 20, 25, 0.18);
            border-color: #94A3B8;
        }
        .message-time {
            font-size: 10px;
            margin-top: 8px;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: 0.05em;
            opacity: 0.7;
        }
        .message-user .message-time { color: #FBBF24; }
        .message-agent .message-time { color: var(--eia-mute); }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Empty state */
        .chat-empty {
            text-align: center;
            padding: 80px 20px;
            color: var(--eia-mute);
        }
        .chat-empty-icon {
            width: 68px; height: 68px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: var(--eia-gold-soft);
            border: 1px solid var(--eia-graphite);
        }

        /* Loading indicator */
        .chat-spinner {
            width: 18px; height: 18px;
            border: 2px solid #E2E8F0;
            border-top-color: var(--eia-black);
            border-right-color: var(--eia-gold);
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Input area */
        .input-area {
            background: var(--eia-surface);
            border-top: 1px solid var(--eia-border);
            padding: 18px 28px;
        }
        .file-upload-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border: 1px solid var(--eia-border);
            border-radius: 10px;
            background: #F8FAFC;
            color: var(--eia-slate);
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
        }
        .file-upload-label:hover {
            background: #FFFFFF;
            border-color: var(--eia-black);
            color: var(--eia-black);
        }
        .chat-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--eia-border);
            border-radius: 12px;
            background: #FFFFFF;
            color: var(--eia-black);
            font-size: 14px;
            outline: none;
            transition: all .2s ease;
        }
        .chat-input:focus {
            border-color: var(--eia-black);
            box-shadow: 0 0 0 3px rgba(15, 20, 25, 0.08);
        }
        .chat-input.has-error {
            border-color: var(--eia-red);
        }
        .chat-input.has-error:focus {
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.15);
        }
        .chat-input::placeholder { color: #94A3B8; }

        .send-button {
            background: var(--eia-black);
            color: #FFFFFF;
            border: 1px solid var(--eia-black);
            padding: 12px 22px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 120px;
        }
        .send-button:hover {
            background: #1F2937;
            border-color: var(--eia-gold);
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.18);
        }
        .send-button:disabled {
            background: #CBD5E1 !important;
            border-color: #CBD5E1 !important;
            box-shadow: none !important;
            color: #94A3B8 !important;
            cursor: not-allowed;
        }

        /* Preview thumbnails */
        .preview-section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--eia-mute);
        }
        .preview-thumb {
            position: relative;
            width: 84px;
        }
        .preview-thumb img {
            width: 84px;
            height: 84px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--eia-border);
        }
        .preview-remove {
            position: absolute;
            top: -6px; right: -6px;
            width: 20px; height: 20px;
            border-radius: 50%;
            background: var(--eia-black);
            color: #FFFFFF;
            border: 2px solid #FFFFFF;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s ease;
        }
        .preview-remove:hover { background: var(--eia-red); }

        .preview-doc {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-radius: 10px;
            background: #FAFAFB;
            border: 1px solid var(--eia-border);
        }
        .preview-doc-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: #FFFFFF;
            border: 1px solid var(--eia-border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Document attached in message */
        .msg-doc {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .message-agent .msg-doc {
            background: #F8FAFC;
            border: 1px solid var(--eia-border);
        }

        .clear-link {
            font-size: 11px;
            font-weight: 600;
            color: var(--eia-red);
            cursor: pointer;
            transition: color .2s ease;
        }
        .clear-link:hover { color: #7F1D1D; }

        /* Image modal */
        .image-modal {
            background: rgba(15, 20, 25, 0.85);
            backdrop-filter: blur(8px);
        }
        .image-modal-close {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(4px);
            transition: all .2s ease;
        }
        .image-modal-close:hover {
            background: var(--eia-red);
            border-color: var(--eia-red);
        }

        /* ---- Markdown renderizado (respuestas del bot) ---- */
        .md-content { color: var(--eia-black); word-wrap: break-word; overflow-wrap: anywhere; }
        .md-content > *:first-child { margin-top: 0; }
        .md-content > *:last-child { margin-bottom: 0; }
        .md-content p { margin: 0 0 10px; line-height: 1.6; }
        .md-content h1,
        .md-content h2,
        .md-content h3,
        .md-content h4 {
            font-weight: 700;
            line-height: 1.3;
            margin: 16px 0 8px;
            color: var(--eia-black);
        }
        .md-content h1 { font-size: 1.25rem; }
        .md-content h2 { font-size: 1.15rem; }
        .md-content h3 { font-size: 1.05rem; }
        .md-content h4 { font-size: 0.95rem; }
        .md-content strong { font-weight: 700; color: var(--eia-black); }
        .md-content em { font-style: italic; }
        .md-content ul,
        .md-content ol { margin: 0 0 10px; padding-left: 1.35em; }
        .md-content ul { list-style: disc; }
        .md-content ol { list-style: decimal; }
        .md-content li { margin: 4px 0; line-height: 1.55; }
        .md-content li > ul,
        .md-content li > ol { margin: 4px 0 4px; }
        .md-content a {
            color: var(--eia-red);
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .md-content a:hover { color: #7F1D1D; }
        .md-content blockquote {
            margin: 0 0 10px;
            padding: 6px 14px;
            border-left: 3px solid var(--eia-gold);
            background: #FFFBEB;
            color: var(--eia-slate);
            border-radius: 0 8px 8px 0;
        }
        .md-content code {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 0.85em;
            background: #F1F5F9;
            border: 1px solid var(--eia-border);
            border-radius: 5px;
            padding: 1px 5px;
        }
        .md-content pre {
            margin: 0 0 10px;
            padding: 12px 14px;
            background: #0F1419;
            color: #E2E8F0;
            border-radius: 10px;
            overflow-x: auto;
        }
        .md-content pre code {
            background: transparent;
            border: none;
            padding: 0;
            color: inherit;
            font-size: 0.82rem;
            line-height: 1.5;
        }
        .md-content hr {
            border: none;
            border-top: 1px solid var(--eia-border);
            margin: 14px 0;
        }
        .md-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 10px;
            font-size: 0.85rem;
        }
        .md-content th,
        .md-content td {
            border: 1px solid var(--eia-border);
            padding: 6px 10px;
            text-align: left;
        }
        .md-content th { background: #F8FAFC; font-weight: 700; }
    </style>

    <div class="chat-shell">

        {{-- HEADER --}}
        <header class="chat-hero">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4 flex-1">
                    <a href="/" class="chat-back" aria-label="Volver al inicio">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                        </svg>
                    </a>
                    <div class="evia-avatar-wrap">
                        <div class="gpt-avatar-badge" data-gpt-avatar data-avatar-mode="full"
                             data-fallback-src="{{ asset('storage/img/persona_logo.png') }}"
                             style="width: 52px; height: 52px;" aria-label="EVIA"></div>
                    </div>
                    <div style="flex: 1;">
                        <span class="chat-eyebrow">Asistente </span>
                        <div class="evia-headline mt-1">
                            <span class="evia-headline-sub">EVIA</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-1.5">Hola, soy EVIA. Estoy aquí para ayudarte a encontrar lo que necesites.</p>
                    </div>
                    <div id="chat-avatar-profile-toggle" style="display: flex; flex-direction: column; gap: 8px;">
                        <button type="button" class="avatar-profile-btn" data-profile="field" title="Perfil de campo (EPP)" aria-label="Perfil de campo">⛑️</button>
                        <button type="button" class="avatar-profile-btn" data-profile="exec" title="Perfil ejecutivo" aria-label="Perfil ejecutivo">👔</button>
                    </div>
                </div>

                <button
                    wire:click="clearChat"
                    class="chat-clear-btn"
                    onclick="return confirm('¿Estás seguro de que quieres limpiar el chat?')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Limpiar
                </button>
            </div>

            {{-- Indicador de agente actual --}}
            @if($currentAgentConfig)
            <div class="flex items-center gap-3 mt-4 flex-wrap">
                <div class="agent-chip">
                    <span class="icon">{{ $currentAgentConfig['agent_role']['icon'] ?? '🤖' }}</span>
                    <div class="flex flex-col leading-tight">
                        <strong>{{ $currentAgentConfig['name'] }}</strong>
                        @if($currentAgentConfig['is_user_setting'] && $currentAgentConfig['custom_prompt'])
                        <span class="text-[10px] uppercase tracking-widest" style="color: var(--eia-gold-soft);">Personalizado</span>
                        @endif
                    </div>
                </div>

                <button
                    wire:click="toggleAgentSelector"
                    class="agent-change-btn"
                    title="Cambiar agente">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Cambiar
                </button>
            </div>
            @endif
        </header>

        {{-- Selector de agente --}}
        @if($showAgentSelector)
        <div class="px-7 pt-4">
            <div class="agent-selector">
                <h3 class="mb-4">Seleccionar agente</h3>

                <div class="mb-5">
                    <h4 class="mb-3">Roles predefinidos</h4>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                        @foreach($availableAgentRoles as $role)
                        <button
                            wire:click="changeAgent('role', {{ $role['id'] }})"
                            class="agent-tile {{ $currentAgentConfig && !$currentAgentConfig['is_user_setting'] && $currentAgentConfig['agent_role']['id'] == $role['id'] ? 'active' : '' }}">
                            <span class="text-lg flex-shrink-0">{{ $role['icon'] }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold truncate">{{ $role['name'] }}</div>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                @if(count($userAgentSettings) > 0)
                <div>
                    <h4 class="mb-3">Mis configuraciones</h4>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                        @foreach($userAgentSettings as $setting)
                        <button
                            wire:click="changeAgent('setting', {{ $setting['id'] }})"
                            class="agent-tile user-config {{ $currentAgentConfig && $currentAgentConfig['is_user_setting'] && $currentAgentConfig['id'] == $setting['id'] ? 'active' : '' }}">
                            <span class="text-lg flex-shrink-0">{{ $setting['agent_role']['icon'] }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold truncate">{{ $setting['name'] }}</div>
                                <div class="text-xs text-slate-500 truncate">{{ $setting['agent_role']['name'] }}</div>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-4 flex justify-end">
                    <button
                        wire:click="toggleAgentSelector"
                        class="text-xs font-semibold text-slate-600 hover:text-slate-900 uppercase tracking-widest">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Messages --}}
        <div class="messages-area" id="messages-container">
            @forelse($messages as $msg)
                <div class="flex {{ $msg['emisor_id'] == auth()->id() ? 'justify-end' : 'justify-start' }} mb-5">
                    <div class="max-w-xs lg:max-w-md {{ $msg['emisor_id'] == auth()->id() ? 'message-user-container' : 'message-agent-container' }}">
                        <div class="sender-label {{ $msg['emisor_id'] == auth()->id() ? 'sender-label-user' : 'sender-label-agent' }}">
                            @if($msg['emisor_id'] == auth()->id())
                                <span>Tú</span>
                                <span class="sender-dot user"></span>
                            @else
                                <img src="{{ asset('storage/img/persona_logo.png') }}" alt="EVIA" class="evia-avatar-sm evia-avatar-snapshot">
                                <span class="evia-name">EVIA</span>
                                <span class="evia-name-meta">· Asistente</span>
                            @endif
                        </div>

                        <div class="message-container {{ $msg['emisor_id'] == auth()->id() ? 'message-user' : 'message-agent' }}">
                            {{-- Archivos --}}
                            @if(!empty($msg['files']) && count($msg['files']) > 0)
                                <div class="mb-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($msg['files'] as $file)
                                            @if($file['is_image'])
                                                <div class="relative group">
                                                    <img
                                                        src="{{ $file['url'] }}"
                                                        alt="{{ $file['name'] }}"
                                                        class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                        onclick="openImageModal('{{ $file['url'] }}', '{{ $file['name'] }}')">
                                                    <div class="absolute bottom-1 left-1 text-[10px] px-1.5 py-0.5 rounded font-mono" style="background: rgba(15, 20, 25, 0.7); color: #FFFFFF;">
                                                        {{ $file['size'] }}
                                                    </div>
                                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg" style="background: rgba(15, 20, 25, 0.3);">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="msg-doc col-span-2">
                                                    @php $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); @endphp
                                                    <div class="flex-shrink-0 mr-3">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="color: {{ $msg['emisor_id'] == auth()->id() ? 'var(--eia-gold-soft)' : 'var(--eia-red)' }};">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-sm font-medium truncate {{ $msg['emisor_id'] == auth()->id() ? 'text-white' : 'text-slate-900' }}">{{ $file['name'] }}</div>
                                                        <div class="text-[11px] font-mono {{ $msg['emisor_id'] == auth()->id() ? 'text-slate-300' : 'text-slate-500' }}">{{ $file['size'] }} · {{ strtoupper($extension) }}</div>
                                                    </div>
                                                    <a href="{{ $file['url'] }}" download="{{ $file['name'] }}" class="flex-shrink-0 ml-2 transition-colors {{ $msg['emisor_id'] == auth()->id() ? 'text-slate-300 hover:text-white' : 'text-slate-400 hover:text-slate-900' }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($msg['message'])
                                @if($msg['emisor_id'] == auth()->id())
                                    <p class="text-sm whitespace-pre-wrap leading-relaxed">{{ $msg['message'] }}</p>
                                @else
                                    <div class="md-content text-sm leading-relaxed">
                                        {!! \Illuminate\Support\Str::markdown($msg['message'], [
                                            'html_input' => 'strip',
                                            'allow_unsafe_links' => false,
                                        ]) !!}
                                    </div>
                                @endif
                            @endif

                            <p class="message-time">{{ \Carbon\Carbon::parse($msg['created_at'])->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="chat-empty">
                    <div class="gpt-avatar-badge mx-auto mb-4" data-gpt-avatar data-avatar-mode="full"
                         data-fallback-src="{{ asset('storage/img/persona_logo.png') }}"
                         style="width: 84px; height: 84px;" aria-label="EVIA"></div>
                    <p class="text-[10px] uppercase tracking-[0.22em] font-bold mb-2" style="color: var(--eia-gold);">Hola, soy EVIA</p>
                    <p class="text-base font-semibold text-slate-900 mb-1">¿En qué te puedo ayudar hoy?</p>
                    <p class="text-sm text-slate-500 max-w-md mx-auto">Escríbeme una pregunta, adjunta un documento o pídeme que busque algo en la información corporativa.</p>
                </div>
            @endforelse

            @if($isLoading)
                <div class="flex justify-start mb-5">
                    <div class="max-w-xs lg:max-w-md message-agent-container">
                        <div class="sender-label sender-label-agent">
                            <img src="{{ asset('storage/img/persona_logo.png') }}" alt="EVIA" class="evia-avatar-sm">
                            <span class="evia-name">EVIA</span>
                            <span class="evia-name-meta">· Asistente</span>
                        </div>
                        <div class="message-container message-agent">
                            <div class="flex items-center gap-3">
                                <div class="chat-spinner"></div>
                                <p class="text-sm text-slate-700">EVIA está pensando…</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Input area --}}
        <div class="input-area">
            {{-- Preview imágenes --}}
            @if(!empty($previewImages))
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="preview-section-title">Imágenes seleccionadas ({{ count($previewImages) }})</span>
                        <button type="button" wire:click="clearImages" class="clear-link">Limpiar todo</button>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @foreach($previewImages as $index => $preview)
                            <div class="preview-thumb">
                                <img src="{{ $preview['url'] }}" alt="{{ $preview['name'] }}">
                                <button type="button" wire:click="removeImage({{ $index }})" class="preview-remove" aria-label="Quitar imagen">×</button>
                                <div class="text-[10px] text-slate-500 mt-1 truncate" style="max-width:84px;">{{ $preview['name'] }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $preview['size'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Preview documentos --}}
            @if(!empty($previewDocuments))
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="preview-section-title">Documentos seleccionados ({{ count($previewDocuments) }})</span>
                        <button type="button" wire:click="clearDocuments" class="clear-link">Limpiar todo</button>
                    </div>
                    <div class="space-y-2">
                        @foreach($previewDocuments as $index => $preview)
                            <div class="preview-doc">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="preview-doc-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color: var(--eia-slate);">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-900 truncate">{{ $preview['name'] }}</p>
                                        <p class="text-[11px] text-slate-500 font-mono">{{ $preview['size'] }} · {{ strtoupper($preview['type']) }}</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="removeDocument({{ $index }})" class="text-slate-400 hover:text-red-600 transition-colors" aria-label="Quitar documento">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="sendMessage" class="space-y-3">
                <div class="flex items-center gap-2 flex-wrap">
                    <input type="file" wire:model="images" multiple accept="image/*" class="hidden" id="image-upload">
                    <label for="image-upload" class="file-upload-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Adjuntar imágenes
                    </label>

                    <input type="file" wire:model="documents" multiple accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx,.rtf,.csv" class="hidden" id="document-upload">
                    <label for="document-upload" class="file-upload-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Adjuntar documentos
                    </label>

                    <div wire:loading wire:target="images" class="text-xs font-medium" style="color: var(--eia-gold);">Procesando imágenes…</div>
                    <div wire:loading wire:target="documents" class="text-xs font-medium" style="color: var(--eia-gold);">Procesando documentos…</div>
                </div>

                <div class="flex gap-3">
                    <div class="flex-1">
                        <input
                            id="messageInput"
                            type="text"
                            wire:model="message"
                            placeholder="Escribe tu mensaje o adjunta imágenes / documentos…"
                            class="chat-input {{ $errorMessage ? 'has-error' : '' }}"
                            maxlength="1000"
                            wire:keydown.enter="sendMessage">

                        @if($errorMessage)
                            <p class="text-xs mt-1.5" style="color: var(--eia-red);">{{ $errorMessage }}</p>
                        @endif
                    </div>
                    <button
                        type="submit"
                        id="sumbitInputBtn"
                        class="send-button"
                        wire:loading.attr="disabled"
                        wire:target="sendMessage,images">
                        <div wire:loading.remove wire:target="sendMessage,images" class="flex items-center gap-2">
                            <span>Enviar</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <div wire:loading wire:target="sendMessage" class="flex items-center gap-2">
                            <div class="chat-spinner" style="border-color: rgba(255,255,255,0.3); border-top-color: #FFFFFF; border-right-color: var(--eia-gold-soft); width:14px; height:14px;"></div>
                            Enviando…
                        </div>
                        <div wire:loading wire:target="images" class="flex items-center gap-2">
                            <div class="chat-spinner" style="border-color: rgba(255,255,255,0.3); border-top-color: #FFFFFF; border-right-color: var(--eia-gold-soft); width:14px; height:14px;"></div>
                            Subiendo…
                        </div>
                    </button>
                </div>
            </form>

            <div class="text-xs text-slate-500 mt-3 flex items-center justify-between flex-wrap gap-2">
                <div>
                    @if(strlen($message) > 0)
                        <span class="font-mono">{{ strlen($message) }}/1000</span> ·
                    @endif
                    @if(!empty($previewImages))
                        {{ count($previewImages) }} imagen{{ count($previewImages) === 1 ? '' : 'es' }} ·
                    @endif
                    @if(!empty($previewDocuments))
                        {{ count($previewDocuments) }} documento{{ count($previewDocuments) === 1 ? '' : 's' }} ·
                    @endif
                    <span>Presiona Enter para enviar</span>
                </div>
                <div class="text-right text-[11px] text-slate-400">
                    <div>Imágenes: JPG, PNG, GIF, WebP · máx. 2MB · 5 archivos</div>
                    <div>Documentos: PDF, DOC, TXT, XLS, PPT · máx. 10MB · 5 archivos</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de imagen --}}
    <div id="imageModal" class="image-modal fixed inset-0 z-50 hidden items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-5xl max-h-full">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain rounded-xl" style="border: 1px solid rgba(255,255,255,0.15);">
            <button
                onclick="closeImageModal()"
                class="image-modal-close absolute top-4 right-4 rounded-full w-10 h-10 flex items-center justify-center"
                aria-label="Cerrar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div id="modalImageName" class="absolute bottom-4 left-4 px-3 py-2 rounded-lg text-xs font-medium" style="background: rgba(15, 20, 25, 0.75); color: #FFFFFF; border: 1px solid rgba(255,255,255,0.12);"></div>
        </div>
    </div>

@push('scripts')
<script src="{{ asset('js/gpt-avatar.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---- Avatar 3D ---- */
    document.querySelectorAll('[data-gpt-avatar]').forEach(function (el) {
        GPTAvatar.mount(el);
    });

    /* ---- Toggle perfil (campo / ejecutivo) ---- */
    var toggleButtons = document.querySelectorAll('#chat-avatar-profile-toggle .avatar-profile-btn');
    function refreshProfileButtons() {
        toggleButtons.forEach(function (btn) {
            btn.classList.toggle('active', btn.dataset.profile === GPTAvatar.getProfile());
        });
    }
    toggleButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            GPTAvatar.setProfile(this.dataset.profile);
            refreshProfileButtons();
        });
    });
    refreshProfileButtons();

    /* ---- Estado del robot sincronizado con peticiones Livewire ---- */
    document.addEventListener('livewire:request', function () {
        GPTAvatar.setState('thinking');
    });
    document.addEventListener('livewire:response', function () {
        GPTAvatar.setState('idle');
    });

    /* ---- Auto-scroll + re-montar empty-state badge + propagar snapshot ---- */
    document.addEventListener('livewire:updated', function () {
        setTimeout(function () {
            var container = document.getElementById('messages-container');
            if (container) container.scrollTop = container.scrollHeight;

            // Re-montar solo si apareció un badge 3D nuevo (ej. empty state)
            document.querySelectorAll('[data-gpt-avatar]').forEach(function (el) {
                GPTAvatar.mount(el);
            });

            // Aplicar snapshot actual a los sender-labels nuevos
            var snap = GPTAvatar.getSnapshotUrl();
            if (snap) {
                document.querySelectorAll('.evia-avatar-snapshot').forEach(function (img) {
                    if (img.src !== snap) img.src = snap;
                });
            }
        }, 80);
    });

    /* ---- Modal de imagen ---- */
    window.openImageModal = function (imageUrl, imageName) {
        var modal = document.getElementById('imageModal');
        var modalImage = document.getElementById('modalImage');
        var modalImageName = document.getElementById('modalImageName');
        modalImage.src = imageUrl;
        modalImage.alt = imageName;
        modalImageName.textContent = imageName;
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.addEventListener('keydown', function escHandler(e) {
            if (e.key === 'Escape') { closeImageModal(); document.removeEventListener('keydown', escHandler); }
        });
    };
    window.closeImageModal = function () {
        var modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
    };
    document.getElementById('imageModal').addEventListener('click', function (e) {
        if (e.target === this) closeImageModal();
    });

    /* ---- Limpiar input tras enviar ---- */
    window.addEventListener('livewire:initialized', function () {
        Livewire.on('messageSent', function () {
            var inp = document.getElementById('messageInput');
            if (inp) { inp.value = ''; inp.focus(); }
        });
        Livewire.on('chatCleared', function () {
            setTimeout(function () {
                var inp = document.querySelector('input[wire\\:model\\.live="message"]');
                if (inp) inp.focus();
            }, 100);
        });
    });

});
</script>
@endpush
</div>
