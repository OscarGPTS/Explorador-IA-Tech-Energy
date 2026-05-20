{{-- Estilos institucionales compartidos para el bloque admin --}}
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

    .eia-bg { background: var(--eia-bg); }

    /* HERO */
    .admin-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
        position: relative;
    }
    .admin-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .admin-back {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.04);
        display: inline-flex; align-items: center; justify-content: center;
        color: #E2E8F0;
        transition: all .2s ease;
    }
    .admin-back:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--eia-gold);
        color: #FFFFFF;
    }
    .admin-eyebrow {
        font-size: 11px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--eia-gold-soft);
        font-weight: 600;
    }
    .admin-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(255, 255, 255, 0.06);
        color: #FFFFFF;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 600;
        transition: all .2s ease;
        text-decoration: none;
    }
    .admin-action:hover {
        background: rgba(217, 119, 6, 0.15);
        border-color: var(--eia-gold);
    }
    .admin-action.red:hover {
        background: rgba(185, 28, 28, 0.18);
        border-color: var(--eia-red);
    }

    /* Tabs nav */
    .admin-tabs {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 6px;
        display: inline-flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .admin-tab {
        position: relative;
        padding: 9px 16px;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: 0.02em;
        color: var(--eia-slate);
        border-radius: 8px;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .admin-tab:hover { color: var(--eia-black); background: #F1F5F9; }
    .admin-tab.active {
        color: #FFFFFF;
        background: var(--eia-black);
    }
    .admin-tab.active::after {
        content: '';
        position: absolute;
        left: 12px; right: 12px; bottom: 3px;
        height: 2px;
        background: var(--eia-gold);
        border-radius: 2px;
    }

    /* KPI */
    .admin-kpi {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 18px 20px;
        position: relative;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        overflow: hidden;
    }
    .admin-kpi:hover {
        border-color: #94A3B8;
        box-shadow: 0 12px 26px -16px rgba(15, 20, 25, 0.3);
        transform: translateY(-2px);
    }
    .admin-kpi .accent {
        position: absolute;
        left: 0; top: 16px; bottom: 16px;
        width: 3px;
        border-radius: 2px;
        background: var(--eia-red);
    }
    .admin-kpi.gold .accent { background: var(--eia-gold); }
    .admin-kpi.black .accent { background: var(--eia-black); }
    .admin-kpi.slate .accent { background: var(--eia-slate); }
    .admin-kpi-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #F1F5F9;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--eia-border);
    }
    .admin-kpi.red .admin-kpi-icon { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .admin-kpi.gold .admin-kpi-icon { background: #FFFBEB; color: var(--eia-gold); border-color: #FDE68A; }
    .admin-kpi.black .admin-kpi-icon { background: #0F1419; color: #F8FAFC; border-color: #0F1419; }
    .admin-kpi-label {
        font-size: 11px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        font-weight: 600;
    }
    .admin-kpi-value {
        font-size: 26px;
        font-weight: 700;
        color: var(--eia-black);
        line-height: 1;
        margin-top: 6px;
    }

    /* Panel */
    .admin-panel {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
    }
    .admin-panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--eia-border);
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .admin-panel-title {
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .admin-panel-sub {
        font-size: 12px;
        color: var(--eia-mute);
        margin-top: 4px;
    }
    .admin-panel-body { padding: 20px 22px; }

    /* Buttons */
    .admin-btn-primary {
        background: var(--eia-black);
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 10px;
        border: 1px solid var(--eia-black);
        transition: all .2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .admin-btn-primary:hover {
        background: #1F2937;
        border-color: var(--eia-gold);
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
    }
    .admin-btn-secondary {
        background: #FFFFFF;
        color: var(--eia-black);
        font-size: 13px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 10px;
        border: 1px solid var(--eia-border);
        transition: all .2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .admin-btn-secondary:hover {
        background: #F8FAFC;
        border-color: var(--eia-black);
    }
    .admin-btn-danger {
        background: #FFFFFF;
        color: var(--eia-red);
        font-size: 13px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 10px;
        border: 1px solid #FECACA;
        transition: all .2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .admin-btn-danger:hover {
        background: var(--eia-red);
        color: #FFFFFF;
        border-color: var(--eia-red);
    }

    /* Tables */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }
    .admin-table thead th {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--eia-mute);
        text-align: left;
        padding: 12px 18px;
        background: #FAFAFB;
        border-bottom: 1px solid var(--eia-border);
    }
    .admin-table tbody td {
        padding: 14px 18px;
        font-size: 13.5px;
        color: var(--eia-slate);
        border-bottom: 1px solid var(--eia-border);
    }
    .admin-table tbody tr:last-child td { border-bottom: 0; }
    .admin-table tbody tr {
        transition: background .15s ease;
    }
    .admin-table tbody tr:hover { background: #F8FAFC; }
    .admin-table tbody td.primary {
        color: var(--eia-black);
        font-weight: 600;
    }

    /* Bars */
    .admin-bar-track {
        height: 8px;
        background: #F1F5F9;
        border-radius: 999px;
        overflow: hidden;
        position: relative;
        border: 1px solid var(--eia-border);
    }
    .admin-bar-fill {
        height: 100%;
        background: var(--eia-black);
        border-radius: 999px;
        transition: width .35s ease;
    }
    .admin-bar-fill.red { background: var(--eia-red); }
    .admin-bar-fill.gold { background: var(--eia-gold); }

    /* Badges */
    .admin-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 999px;
        background: #F1F5F9;
        color: var(--eia-slate);
        border: 1px solid var(--eia-border);
    }
    .admin-badge.gold { background: #FFFBEB; color: #92400E; border-color: #FDE68A; }
    .admin-badge.red { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .admin-badge.green { background: #ECFDF5; color: #047857; border-color: #A7F3D0; }
    .admin-badge.black { background: #0F1419; color: #F8FAFC; border-color: #0F1419; }

    /* Form inputs */
    .admin-input,
    .admin-textarea,
    .admin-select {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        background: #FFFFFF;
        color: var(--eia-black);
        font-size: 13.5px;
        outline: none;
        transition: all .2s ease;
    }
    .admin-input:focus,
    .admin-textarea:focus,
    .admin-select:focus {
        border-color: var(--eia-black);
        box-shadow: 0 0 0 3px rgba(15, 20, 25, 0.08);
    }
    .admin-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        margin-bottom: 8px;
    }

    /* Spinner */
    .admin-spinner {
        width: 22px; height: 22px;
        border: 2.5px solid #E2E8F0;
        border-top-color: var(--eia-black);
        border-right-color: var(--eia-gold);
        border-radius: 50%;
        animation: adminSpin .8s linear infinite;
        display: inline-block;
    }
    @keyframes adminSpin { to { transform: rotate(360deg); } }

    /* Fade-in */
    .admin-fade { animation: adminFade .55s ease-out both; }
    .admin-d1 { animation-delay: .05s; }
    .admin-d2 { animation-delay: .12s; }
    .admin-d3 { animation-delay: .2s; }
    .admin-d4 { animation-delay: .28s; }
    @keyframes adminFade {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
