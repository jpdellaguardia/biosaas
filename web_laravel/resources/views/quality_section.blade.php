@props(['report'])

@if($report)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        
        <x-metric-card 
            label="Confiabilidade Clínica" 
            value="{{ $report['reliability_score'] }}%"
            color="{{ $report['reliability_score'] > 80 ? 'green' : 'yellow' }}">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-metric-card>

        <x-metric-card 
            label="Variantes Detectadas" 
            value="{{ number_format($report['total_variants'], 0, ',', '.') }}"
            color="indigo">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
        </x-metric-card>

        <x-metric-card 
            label="Alta Precisão (Q30)" 
            value="{{ number_format($report['gold_standard_variants'], 0, ',', '.') }}"
            color="blue">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </x-metric-card>
        
    </div>
@endif