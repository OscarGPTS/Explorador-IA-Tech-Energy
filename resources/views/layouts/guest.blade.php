<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
            html, body { background: var(--eia-bg); }
            body { font-family: 'Figtree', system-ui, -apple-system, sans-serif; }

            .guest-shell {
                min-height: 100vh;
                display: grid;
                grid-template-columns: 1fr;
            }
            @media (min-width: 1024px) {
                .guest-shell { grid-template-columns: 52% 48%; }
            }

            /* Brand panel (oscuro corporativo) */
            .guest-brand {
                position: relative;
                background:
                    radial-gradient(900px 600px at 80% -10%, rgba(217, 119, 6, 0.22), transparent 60%),
                    radial-gradient(700px 500px at -10% 110%, rgba(185, 28, 28, 0.28), transparent 60%),
                    linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
                color: #F8FAFC;
                padding: 48px 56px;
                display: none;
                flex-direction: column;
                justify-content: space-between;
                overflow: hidden;
            }
            @media (min-width: 1024px) {
                .guest-brand { display: flex; }
            }
            .guest-brand::after {
                content: '';
                position: absolute;
                right: 0; top: 0; bottom: 0;
                width: 2px;
                background: linear-gradient(180deg, var(--eia-red) 0%, var(--eia-gold) 100%);
                opacity: 0.85;
            }
            .guest-brand-mark {
                display: inline-flex;
                align-items: center;
                gap: 14px;
            }
            .guest-brand-mark img {
                height: 38px;
                width: auto;
                filter: brightness(0) invert(1);
            }
            .guest-brand-eyebrow {
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 0.28em;
                text-transform: uppercase;
                color: var(--eia-gold-soft);
                line-height: 1;
                margin-bottom: 4px;
                display: block;
            }
            .guest-brand-name {
                font-size: 18px;
                font-weight: 600;
                color: #FFFFFF;
                line-height: 1;
                letter-spacing: -0.01em;
            }

            .guest-brand-message {
                max-width: 460px;
            }
            .guest-brand-headline {
                font-size: 38px;
                font-weight: 600;
                line-height: 1.15;
                letter-spacing: -0.02em;
                color: #FFFFFF;
            }
            .guest-brand-headline em {
                font-style: normal;
                color: var(--eia-gold-soft);
            }
            .guest-brand-sub {
                margin-top: 18px;
                color: #94A3B8;
                font-size: 14.5px;
                line-height: 1.6;
                max-width: 420px;
            }

            .guest-persona-img {
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 420px;
                height: auto;
                object-fit: contain;
                pointer-events: none;
                -webkit-mask-image:
                    linear-gradient(to left,  rgba(0,0,0,0) 0%, rgba(0,0,0,0.5) 18%, rgba(0,0,0,1) 48%),
                    linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 12%, rgba(0,0,0,1) 72%, rgba(0,0,0,0) 100%);
                mask-image:
                    linear-gradient(to left,  rgba(0,0,0,0) 0%, rgba(0,0,0,0.5) 18%, rgba(0,0,0,1) 48%),
                    linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 12%, rgba(0,0,0,1) 72%, rgba(0,0,0,0) 100%);
                -webkit-mask-composite: source-in;
                mask-composite: intersect;
            }

            .guest-brand-pillars {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
                margin-top: 28px;
            }
            .guest-pillar {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 7px 12px;
                border: 1px solid rgba(255, 255, 255, 0.14);
                background: rgba(255, 255, 255, 0.04);
                border-radius: 999px;
                font-size: 11.5px;
                font-weight: 600;
                color: #E2E8F0;
                letter-spacing: 0.02em;
            }
            .guest-pillar .dot {
                width: 6px; height: 6px;
                border-radius: 50%;
                background: var(--eia-gold);
            }
            .guest-pillar.red .dot { background: var(--eia-red); }

            .guest-brand-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 11px;
                color: #64748B;
                letter-spacing: 0.04em;
                gap: 12px;
                flex-wrap: wrap;
            }
            .guest-brand-footer-eyebrow {
                font-size: 10px;
                letter-spacing: 0.22em;
                text-transform: uppercase;
                color: var(--eia-gold-soft);
                font-weight: 700;
            }

            /* Form side */
            .guest-form {
                background: #FFFFFF;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 48px 32px;
                position: relative;
            }
            .guest-form-mobile-brand {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 32px;
            }
            @media (min-width: 1024px) {
                .guest-form-mobile-brand { display: none; }
            }
            .guest-form-mobile-brand img {
                height: 32px;
                width: auto;
            }

            .guest-form-card {
                width: 100%;
                max-width: 420px;
            }

            .guest-form-eyebrow {
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 0.24em;
                text-transform: uppercase;
                color: var(--eia-red);
            }
            .guest-form-title {
                font-size: 26px;
                font-weight: 600;
                color: var(--eia-black);
                letter-spacing: -0.02em;
                margin-top: 8px;
                line-height: 1.2;
            }
            .guest-form-sub {
                font-size: 14px;
                color: var(--eia-slate);
                margin-top: 8px;
                line-height: 1.55;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="guest-shell">

            {{-- Lado branding institucional --}}
            <aside class="guest-brand">
                <div class="guest-brand-mark">
                    <img src="{{ asset('storage/img/logo.png') }}" alt="Logo">
                    
                </div>

                <img src="{{ asset('storage/img/persona_logo.png') }}" alt="" class="guest-persona-img">

                <div class="guest-brand-message">
                    <h2 class="guest-brand-headline">
                        Explorador  <em>IA</em>
                    </h2>
                    <p class="guest-brand-sub">
                        Plataforma empresarial que centraliza IA para mejorar el acceso a la información, automatizar procesos internos y brindar asistencia inteligente mediante módulos especializados de consulta, búsqueda documental, soporte técnico y recomendaciones automatizadas.
                    </p>

                    <div class="guest-brand-pillars">
                        <span class="guest-pillar"><span class="dot"></span>Chat Inteligente</span>
                        <span class="guest-pillar"><span class="dot"></span>Búsqueda Documental</span>
                        <span class="guest-pillar red"><span class="dot"></span>Noticias por Área</span>
                        <span class="guest-pillar"><span class="dot"></span>Recomendaciones</span>
                        <span class="guest-pillar red"><span class="dot"></span>Soporte Técnico</span>
                    </div>
                </div>

                <div class="guest-brand-footer">
                    <div>
                        <p class="guest-brand-footer-eyebrow">Plataforma de uso interno corporativo.</p>
                    </div>
                    <p>© {{ date('Y') }} · Todos los derechos reservados</p>
                </div>
            </aside>

            {{-- Lado formulario --}}
            <main class="guest-form">
                <div class="guest-form-card">
                    <div class="guest-form-mobile-brand">
                        <img src="{{ asset('storage/img/logo.png') }}" alt="Logo">
                        <span class="text-base font-semibold text-slate-900">Explorador IA</span>
                    </div>
                    @yield('content')
                </div>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    </body>
</html>
