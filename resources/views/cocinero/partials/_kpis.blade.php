{{-- NOTIFICACIONES Y KPIS DE COCINA --}}
@if(session('success'))
    <div style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        ⚠️ {{ session('error') }}
    </div>
@endif

{{-- KPI CARDS --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <div>
            <span class="kpi-label">PENDIENTES</span>
            <strong class="kpi-value">{{ $pendientes }}</strong>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon amber">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 13.8a6 6 0 0 1 12 0"/><path d="M12 2v4"/><path d="M4 14h16v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6z"/><line x1="12" y1="18" x2="12" y2="18.01"/>
            </svg>
        </div>
        <div>
            <span class="kpi-label">EN PREPARACIÓN</span>
            <strong class="kpi-value">{{ $enPreparacion }}</strong>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div>
            <span class="kpi-label">URGENTES</span>
            <strong class="kpi-value">{{ $urgentes }}</strong>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="16" rx="3"/><polyline points="12 8 12 12 15 14"/>
            </svg>
        </div>
        <div>
            <span class="kpi-label">CON HORA ENTREGA</span>
            <strong class="kpi-value">{{ $conHoraEntrega }}</strong>
        </div>
    </div>
</div>
