@props(['report'])

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center border border-slate-100">
        <div class="text-slate-400 text-xs uppercase font-bold tracking-wider">Status</div>
        <div class="text-xl font-extrabold mt-2 {{ ($report['status'] ?? '') == 'APPROVED' ? 'text-green-600' : 'text-yellow-600' }}">
            {{ $report['status'] ?? 'N/A' }}
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center border border-slate-100">
        <div class="text-slate-400 text-xs uppercase font-bold tracking-wider">Score</div>
        <div class="text-2xl font-bold text-indigo-600 mt-2">
            {{ $report['reliability_score'] ?? 0 }}%
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center border border-slate-100">
        <div class="text-slate-400 text-xs uppercase font-bold tracking-wider">Variantes</div>
        <div class="text-2xl font-bold text-slate-700 mt-2">
            {{ number_format($report['total_variants'] ?? 0) }}
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center border-2 border-indigo-50">
        <div class="text-indigo-400 text-xs uppercase font-bold tracking-wider">Gold Standard</div>
        <div class="text-2xl font-bold text-indigo-700 mt-2">
            {{ number_format($report['gold_standard_variants'] ?? 0) }}
        </div>
    </div>
</div>