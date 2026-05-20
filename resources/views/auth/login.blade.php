@extends('layouts.guest')

@section('content')
<style>
    .login-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 20px 0;
        color: #94A3B8;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.22em;
        text-transform: uppercase;
    }
    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--eia-border);
    }

    .login-google-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        padding: 13px 20px;
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        color: var(--eia-black);
        font-size: 14px;
        font-weight: 600;
        transition: all .2s ease;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    .login-google-btn::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-black);
        opacity: 0;
        transition: opacity .2s ease;
    }
    .login-google-btn:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -8px rgba(15, 20, 25, 0.2);
    }
    .login-google-btn:hover::before { opacity: 1; }
    .login-google-btn img { width: 22px; height: 22px; }

    .login-help-link {
        font-size: 12.5px;
        color: var(--eia-slate);
        font-weight: 500;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        transition: color .15s ease;
        border-bottom: 1px solid transparent;
    }
    .login-help-link:hover {
        color: var(--eia-red);
        border-bottom-color: var(--eia-red);
    }

    .login-features {
        margin-top: 36px;
        padding-top: 28px;
        border-top: 1px solid var(--eia-border);
    }
    .login-features-title {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--eia-mute);
        margin-bottom: 14px;
    }
    .login-feature {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        font-size: 12.5px;
        color: var(--eia-slate);
    }
    .login-feature svg {
        flex-shrink: 0;
        margin-top: 2px;
        color: var(--eia-gold);
    }

    /* Modal */
    .eia-modal-backdrop {
        background: rgba(15, 20, 25, 0.6);
        backdrop-filter: blur(4px);
    }
    .eia-modal-shell {
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 30px 60px -20px rgba(15, 20, 25, 0.4);
    }
    .eia-modal-icon-wrap {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: #FFFBEB;
        color: var(--eia-gold);
        border: 1px solid #FDE68A;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .eia-modal-close-btn {
        width: 34px; height: 34px;
        border-radius: 8px;
        background: #F1F5F9;
        color: var(--eia-slate);
        border: 1px solid var(--eia-border);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }
    .eia-modal-close-btn:hover {
        background: var(--eia-black);
        color: #FFFFFF;
        border-color: var(--eia-black);
    }
</style>

<div>
    <p class="guest-form-eyebrow">Acceso · Cuenta corporativa</p>
    <h1 class="guest-form-title">Inicia sesión en la plataforma</h1>
    <p class="guest-form-sub">
        Autenticación segura mediante tu cuenta empresarial de Google. Tu acceso está vinculado al directorio corporativo.
    </p>

    <div class="login-divider">Identificación</div>

    <a href="auth/google" class="login-google-btn">
        <img src="{{ asset('storage/img/google.png') }}" alt="Google">
        Continuar con Google
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left: auto;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
    </a>

    <div class="flex justify-end mt-5">
        <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="login-help-link" type="button">
            ¿No cuentas con acceso?
        </button>
    </div>

    <div class="login-features">
        <p class="login-features-title">Plataforma incluye</p>
        <div class="login-feature">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Buscador inteligente y asistente IA conversacional
        </div>
        <div class="login-feature">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Acceso al repositorio documental corporativo
        </div>
        <div class="login-feature">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Soporte técnico asistido 24/7
        </div>
    </div>
</div>

{{-- Modal "Sin acceso" --}}
<div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full eia-modal-backdrop">
    <div class="relative p-4 w-full max-w-md max-h-full mx-auto" style="margin-top: 14vh;">
        <div class="eia-modal-shell">
            <div class="flex justify-end p-3">
                <button type="button" class="eia-modal-close-btn" data-modal-hide="popup-modal" aria-label="Cerrar">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <div class="px-8 pb-8 text-center">
                <div class="eia-modal-icon-wrap mx-auto mb-4">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18 9 9 0 010-18z"/>
                    </svg>
                </div>
                <p class="text-[10px] uppercase tracking-[0.22em] font-bold" style="color: var(--eia-gold);">Sin acceso</p>
                <h3 class="text-lg font-semibold text-slate-900 mt-2">Solicitud de habilitación</h3>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Comunícate con el equipo de Sistemas para gestionar la habilitación de tu cuenta corporativa.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
