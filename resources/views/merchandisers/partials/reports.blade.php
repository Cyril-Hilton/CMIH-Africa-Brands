<section x-show="activeTab === 'reports'" class="space-y-5" x-cloak>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-brand-red">My records</p>
            <h2 class="mt-1 text-2xl font-bold text-brand-white">Reports</h2>
            <p class="mt-1 text-sm text-brand-ash">Download your current month field records or print this summary as PDF.</p>
        </div>
        <button type="button" onclick="window.print()" class="min-h-10 rounded-lg border border-brand-red px-4 text-xs font-bold text-brand-red">Print / save PDF</button>
    </div>

    @php
        $reportCards = [
            ['type' => 'coverage', 'title' => 'Coverage report', 'description' => 'Scheduled outlets, route order, completion state, and dates.', 'value' => number_format($merchMetrics['coverage_today'] ?? 0, 1).'%'],
            ['type' => 'kpis', 'title' => 'KPI performance report', 'description' => 'Outlet-level Facings, Planogram, SOS, and Perfect Store scores.', 'value' => number_format($merchMetrics['perfect_store_score'] ?? 0, 1).'%'],
            ['type' => 'visits', 'title' => 'Outlet visit report', 'description' => 'Submitted visits, evidence counts, entry mode, and sync state.', 'value' => $merchMetrics['outlets_covered_month'] ?? 0],
            ['type' => 'photos', 'title' => 'Photo compliance report', 'description' => 'Recorded SKU evidence mapped to outlet and visit time.', 'value' => $merchMetrics['photos_uploaded_month'] ?? 0],
            ['type' => 'summary', 'title' => 'Summary report', 'description' => 'A concise current-month coverage and execution overview.', 'value' => now()->format('M Y')],
        ];
    @endphp
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach($reportCards as $report)
            <article class="merch-card flex min-h-52 flex-col p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-brand-white">{{ $report['title'] }}</p>
                        <p class="mt-2 text-[11px] leading-5 text-brand-ash">{{ $report['description'] }}</p>
                    </div>
                    <span class="rounded-lg bg-brand-red/10 px-3 py-2 text-sm font-bold text-brand-red">{{ $report['value'] }}</span>
                </div>
                <div class="mt-auto pt-5">
                    <a href="{{ route('merchandisers.reports.download', ['type' => $report['type']]) }}" class="merch-primary-button inline-flex min-h-10 w-full items-center justify-center rounded-lg px-4 text-xs font-bold">Download CSV</a>
                </div>
            </article>
        @endforeach
    </div>

    <section class="merch-card p-5">
        <div class="grid gap-4 sm:grid-cols-4">
            @foreach([
                ['label' => 'Scheduled today', 'value' => $merchMetrics['assigned_outlets_today'] ?? 0],
                ['label' => 'Completed today', 'value' => $merchMetrics['outlets_scored_today'] ?? 0],
                ['label' => 'Pending sync', 'value' => $merchMetrics['pending_sync'] ?? 0],
                ['label' => 'Evidence MTD', 'value' => $merchMetrics['photos_uploaded_month'] ?? 0],
            ] as $item)
                <div class="border-b border-brand-white/10 pb-3 sm:border-b-0 sm:border-r sm:pb-0 sm:pr-4 last:border-0">
                    <p class="text-[9px] font-bold uppercase tracking-[0.12em] text-brand-ash">{{ $item['label'] }}</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-brand-white">{{ $item['value'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
</section>
