<div id="processing-panel" class="hidden text-center py-10">
    <div class="relative w-20 h-20 mx-auto mb-6">
        <div class="absolute inset-0 border-4 border-slate-200 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-500 rounded-full border-t-transparent animate-spin"></div>
        <svg class="absolute inset-0 w-10 h-10 m-auto text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
        </svg>
    </div>

    <h3 class="text-xl font-semibold text-slate-800 mb-2">Sequenciando DNA...</h3>
    <p id="loading-text" class="text-slate-500 mb-6 text-sm">Iniciando pipeline de bioinformática</p>

    <div class="w-full max-w-md mx-auto bg-slate-200 rounded-full h-4 overflow-hidden shadow-inner">
        <div id="progress-bar" 
             class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full transition-all duration-500 ease-out" 
             style="width: 0%">
        </div>
    </div>
    
    <p class="mt-2 text-xs text-slate-400 font-mono"><span id="percent-text">0</span>%</p>
</div>