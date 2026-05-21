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

    .eia-topbar {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 40;
        background: #FFFFFF;
        border-bottom: 1px solid var(--eia-border);
        height: 64px;
        box-shadow: 0 1px 0 rgba(15, 20, 25, 0.02);
    }
    .eia-topbar::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: -1px;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .eia-topbar-inner {
        max-width: 100%;
        padding: 0 20px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .eia-brand {
        display: inline-flex;
        align-items: center;
        gap: 14px;
        color: var(--eia-black);
        text-decoration: none;
    }
    .eia-brand-corp {
        height: 34px;
        width: auto;
    }
    .eia-brand-divider {
        width: 1px;
        height: 28px;
        background: var(--eia-border);
    }
    .eia-brand-platform {
        height: 32px;
        width: auto;
    }
    .eia-brand-title {
        font-size: 16px;
        font-weight: 600;
        letter-spacing: -0.01em;
        color: var(--eia-black);
        line-height: 1;
    }
    .eia-brand-eyebrow {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.24em;
        text-transform: uppercase;
        color: var(--eia-red);
        display: block;
        line-height: 1;
        margin-bottom: 4px;
    }

    .eia-top-actions {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .eia-top-icon-btn {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid var(--eia-border);
        background: #FFFFFF;
        color: var(--eia-slate);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }
    .eia-top-icon-btn:hover {
        background: #F8FAFC;
        border-color: var(--eia-black);
        color: var(--eia-black);
    }

    .eia-user-trigger {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 4px 14px 4px 4px;
        border-radius: 999px;
        border: 1px solid var(--eia-border);
        background: #FFFFFF;
        color: var(--eia-black);
        transition: all .2s ease;
        cursor: pointer;
    }
    .eia-user-trigger:hover {
        background: #F8FAFC;
        border-color: var(--eia-black);
    }
    .eia-user-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid var(--eia-black);
        background: #F1F5F9;
        position: relative;
    }
    .eia-user-avatar-wrap {
        position: relative;
    }
    .eia-user-avatar-wrap::after {
        content: '';
        position: absolute;
        bottom: -1px; right: -1px;
        width: 9px; height: 9px;
        background: #10B981;
        border: 2px solid #FFFFFF;
        border-radius: 50%;
    }
    .eia-user-name {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--eia-black);
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Dropdowns */
    .eia-dropdown {
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 14px;
        box-shadow: 0 24px 48px -16px rgba(15, 20, 25, 0.18);
        overflow: hidden;
        min-width: 260px;
    }
    .eia-dropdown-head {
        background: linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #FFFFFF;
        padding: 18px 20px;
        position: relative;
    }
    .eia-dropdown-head::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .eia-dropdown-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--eia-gold-soft);
    }
    .eia-dropdown-sub {
        font-size: 13.5px;
        color: #FFFFFF;
        margin-top: 4px;
        font-weight: 600;
    }
    .eia-dropdown-email {
        font-size: 11.5px;
        color: #94A3B8;
        margin-top: 2px;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .eia-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        font-size: 13px;
        font-weight: 500;
        color: var(--eia-black);
        background: #FFFFFF;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        transition: all .15s ease;
    }
    .eia-dropdown-item:hover {
        background: #F8FAFC;
        color: var(--eia-black);
    }
    .eia-dropdown-item svg { color: var(--eia-mute); transition: color .15s ease; }
    .eia-dropdown-item:hover svg { color: var(--eia-black); }
    .eia-dropdown-item.danger:hover {
        background: #FEF2F2;
        color: var(--eia-red);
    }
    .eia-dropdown-item.danger:hover svg { color: var(--eia-red); }

    /* Apps dropdown */
    .eia-apps-dropdown {
        right: 16px !important;
        left: auto !important;
        top: 70px !important;
        min-width: 380px;
        max-width: 440px;
    }
    .eia-apps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        padding: 18px;
    }
    @media (max-width: 640px) {
        .eia-apps-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .eia-app-tile {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 14px 10px;
        border: 1px solid var(--eia-border);
        background: #FFFFFF;
        border-radius: 10px;
        text-decoration: none;
        text-align: center;
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
    }
    .eia-app-tile::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-black);
        opacity: 0;
        transition: opacity .2s ease;
    }
    .eia-app-tile:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        transform: translateY(-2px);
    }
    .eia-app-tile:hover::before { opacity: 1; }
    .eia-app-tile.red:hover::before { background: var(--eia-red); }
    .eia-app-tile.gold:hover::before { background: var(--eia-gold); }
    .eia-app-tile.locked {
        cursor: not-allowed;
        border-style: dashed;
        background: #FAFAFB;
    }
    .eia-app-tile.locked:hover {
        transform: none;
        border-color: var(--eia-border);
    }
    .eia-app-tile.locked:hover::before { opacity: 0; }

    .eia-app-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: #F1F5F9;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--eia-border);
    }
    .eia-app-tile.red .eia-app-icon { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .eia-app-tile.gold .eia-app-icon { background: #FFFBEB; color: var(--eia-gold); border-color: #FDE68A; }
    .eia-app-tile.locked .eia-app-icon { background: #E2E8F0; color: #94A3B8; border-color: #E2E8F0; }
    .eia-app-title {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--eia-black);
        line-height: 1.3;
        letter-spacing: -0.005em;
    }
    .eia-app-tile.locked .eia-app-title { color: #94A3B8; }

    .eia-apps-footer {
        padding: 12px 18px;
        background: #FAFAFB;
        border-top: 1px solid var(--eia-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11px;
        color: var(--eia-mute);
    }
    .eia-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }
    .eia-status-dot-live {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    /* Logout modal */
    .eia-modal-backdrop {
        background: rgba(15, 20, 25, 0.6);
        backdrop-filter: blur(4px);
    }
    .eia-modal-shell {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 30px 60px -20px rgba(15, 20, 25, 0.4);
    }
    .eia-modal-icon-wrap {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: #FEF2F2;
        color: var(--eia-red);
        border: 1px solid #FECACA;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-modal-primary {
        background: var(--eia-red);
        color: #FFFFFF;
        border: 1px solid var(--eia-red);
        font-size: 13px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 10px;
        transition: all .2s ease;
    }
    .btn-modal-primary:hover {
        background: #7F1D1D;
        border-color: #7F1D1D;
    }
    .btn-modal-secondary {
        background: #FFFFFF;
        color: var(--eia-slate);
        border: 1px solid var(--eia-border);
        font-size: 13px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 10px;
        transition: all .2s ease;
    }
    .btn-modal-secondary:hover {
        background: #F1F5F9;
        color: var(--eia-black);
        border-color: var(--eia-black);
    }
</style>

<nav class="eia-topbar">
    <div class="eia-topbar-inner">

        {{-- Brand --}}
        <a href="/" class="eia-brand">
            <img src="{{ asset('storage/img/logo.png') }}" alt="Logo corporativo" class="eia-brand-corp">
            <span class="eia-brand-divider hidden sm:inline-block"></span>
            <img src="{{ asset('storage/img/logo_clean.png') }}" alt="Explorador IA" class="eia-brand-platform hidden sm:inline-block">
            <div class="hidden md:block">
                <span class="eia-brand-title">Explorador IA</span>
            </div>
        </a>

        {{-- Actions --}}
        <div class="eia-top-actions">

            {{-- Apps grid trigger --}}
            <button type="button" data-dropdown-toggle="apps-dropdown" data-dropdown-placement="bottom-end"
                    class="eia-top-icon-btn hidden sm:inline-flex" aria-label="Aplicaciones">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
            </button>

            {{-- User trigger --}}
            <button type="button" data-dropdown-toggle="dropdown-2" data-dropdown-placement="bottom-end"
                    class="eia-user-trigger" id="user-menu-button-2" aria-expanded="false">
                <span class="eia-user-avatar-wrap">
                    <img class="eia-user-avatar"
                         src="{{ isset(auth()->user()->google_image) ? auth()->user()->google_image : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=ffffff&background=0F1419' }}"
                         alt="{{ auth()->user()->name }}">
                </span>
                <span class="eia-user-name hidden sm:inline">{{ auth()->user()->name }}</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="hidden sm:inline" style="color:#94A3B8;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- User dropdown --}}
            <div class="z-50 hidden eia-dropdown" id="dropdown-2">
                <div class="eia-dropdown-head">
                    <p class="eia-dropdown-title">Cuenta</p>
                    <p class="eia-dropdown-sub">{{ auth()->user()->name }}</p>
                    <p class="eia-dropdown-email">{{ auth()->user()->email }}</p>
                </div>
                <div class="py-1">
                    <a href="{{ route('profile.index') }}" class="eia-dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 10-8 0M12 11a3 3 0 100-6 3 3 0 000 6zM4 21a8 8 0 0116 0"/>
                        </svg>
                        Mi perfil
                    </a>
                    <button data-modal-target="logout-modal" data-modal-toggle="logout-modal" type="button" class="eia-dropdown-item danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h6a2 2 0 012 2v1"/>
                        </svg>
                        Cerrar sesión
                    </button>
                </div>
            </div>

            {{-- Apps dropdown --}}
            <div class="eia-apps-dropdown eia-dropdown z-50 hidden absolute" id="apps-dropdown">
                <div class="eia-dropdown-head">
                    <p class="eia-dropdown-title">Aplicaciones</p>
                    <p class="eia-dropdown-sub">Herramientas inteligentes</p>
                </div>

                <div class="eia-apps-grid">
                    <a href="{{ route('chat.index') }}" class="eia-app-tile black">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Buscador IA</span>
                    </a>

                    <a href="{{ route('recommendations.index') }}" class="eia-app-tile gold">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.091z"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Recomendaciones</span>
                    </a>

                    <a href="{{ route('news.index') }}" class="eia-app-tile red">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 10h6M7 14h10M7 18h7"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Noticias</span>
                    </a>

                    <a href="{{ route('admin.stats.dashboard') }}" class="eia-app-tile">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Estadísticas</span>
                    </a>

                    <a href="{{ route('admin.employees.index') }}" class="eia-app-tile">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 10-8 0M12 11a3 3 0 100-6 3 3 0 000 6zM4 21a8 8 0 0116 0M20 8v3M20 11h3M20 11h-3"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Empleados</span>
                    </a>

                    <a href="{{ route('tech-support.index') }}" class="eia-app-tile red">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3m-3 12h3M5 12h14M7 6h.01M7 18h.01M5 6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6z"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Soporte</span>
                    </a>

                    <a href="{{ route('document-bot.index') }}" class="eia-app-tile gold">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9V5a2 2 0 00-2-2h-7l-5 5v11a2 2 0 002 2h6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v4a2 2 0 002 2h4M16 17h6M19 14v6"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Documentos</span>
                    </a>

                    <div class="eia-app-tile locked">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Próximo</span>
                    </div>

                    <div class="eia-app-tile locked">
                        <div class="eia-app-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="eia-app-title">Próximo</span>
                    </div>
                </div>

                <div class="eia-apps-footer">
                    <span class="eia-status-pill">
                        <span class="eia-status-dot-live"></span>
                        Sistema activo
                    </span>
                    <span>7 aplicaciones</span>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- Logout modal --}}
<div id="logout-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full eia-modal-backdrop">
    <div class="relative p-4 w-full max-w-md max-h-full mx-auto" style="margin-top: 12vh;">
        <div class="eia-modal-shell">
            <div class="p-6 text-center">
                <div class="eia-modal-icon-wrap mx-auto mb-4">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h6a2 2 0 012 2v1"/>
                    </svg>
                </div>
                <p class="text-[10px] uppercase tracking-[0.22em] font-bold" style="color: var(--eia-red);">Cerrar sesión</p>
                <h3 class="text-lg font-semibold text-slate-900 mt-2">¿Deseas cerrar tu sesión?</h3>
                <p class="text-sm text-slate-500 mt-1.5 mb-6">Tendrás que volver a autenticarte para acceder.</p>

                <div class="flex items-center justify-center gap-3">
                    <button data-modal-hide="logout-modal" type="button" class="btn-modal-secondary">
                        Cancelar
                    </button>
                    <form method="POST" action="{{ route('google.logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="btn-modal-primary">
                            Sí, cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
