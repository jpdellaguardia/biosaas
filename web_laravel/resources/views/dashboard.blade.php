<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BioSaaS - Painel Genômico</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen py-10 font-sans text-slate-900">
    <div class="max-w-6xl mx-auto px-4">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="p-8 md:p-12 text-center">
                <h1 class="text-4xl font-extrabold text-slate-800 mb-2 tracking-tight">Painel Genômico 🧬</h1>
                <p class="text-slate-500 mb-8 text-lg">Carregue seu arquivo VCF ou TXT para descobrir sua ancestralidade.</p>

                @if (session('status'))
                    <div id="processing-panel" class="max-w-md mx-auto mb-8 bg-indigo-50 rounded-xl p-6 border border-indigo-100">
                        <div class="flex justify-between mb-2">
                            <span id="loading-text" class="text-xs font-bold uppercase text-indigo-600 animate-pulse">Iniciando...</span>
                            <span class="text-xs font-bold text-indigo-600"><span id="percent-text">0</span>%</span>
                        </div>
                        <div class="w-full bg-indigo-200 rounded-full h-2.5">
                            <div id="progress-bar" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                    <script>document.addEventListener('DOMContentLoaded', function() { startProgressSimulation(); });</script>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 max-w-md mx-auto">
                        <strong class="font-bold">Ops!</strong> <span class="block sm:inline">{{ $errors->first() }}</span>
                    </div>
                @endif
<div class="p-8 md:p-12 text-center">
    <div class="mb-8 p-4 bg-blue-50 border border-blue-100 rounded-xl max-w-2xl mx-auto">
        <h3 class="text-blue-800 font-bold text-sm uppercase tracking-wide mb-3">🎓 Área do Estudante / Professor</h3>
        <p class="text-blue-600 text-xs mb-4">Não tem um arquivo VCF? Carregue um paciente virtual para estudo.</p>
        
        <div class="flex flex-wrap justify-center gap-3">
            <form action="/analyze" method="POST" class="inline">
                @csrf
                <input type="hidden" name="demo_case" value="SICKLE_CELL">
                <button type="submit" class="bg-white text-blue-600 border border-blue-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-100 transition-colors flex items-center gap-2">
                    🩸 Caso: Anemia Falciforme
                </button>
            </form>

            <form action="/analyze" method="POST" class="inline">
                @csrf
                <input type="hidden" name="demo_case" value="WARFARIN">
                <button type="submit" class="bg-white text-blue-600 border border-blue-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-100 transition-colors flex items-center gap-2">
                    💊 Caso: Varfarina
                </button>
            </form>
        </div>
    </div>

    ```

---

### Passo 2: Adicionar a Visualização (O código que você pediu)
Agora, vamos encaixar o HTML que mostra o resultado. Coloque isso **depois** da div do Mapa 3D ou logo após os cards de estatísticas.

```html
@if(isset($quality) && $quality['status'] != 'PENDING')
    
    @if(isset($quality['mode']) && $quality['mode'] === 'demo_case')
        <div class="mt-8 bg-white border-l-4 border-blue-500 shadow-md rounded-r-xl p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-bl-lg">MODO DIDÁTICO</div>
            
            <h2 class="text-2xl font-bold text-slate-800 mb-1">{{ $quality['data']['condition'] ?? 'Condição Genética' }}</h2>
            <div class="flex gap-4 text-sm text-slate-500 mb-6">
                <p><strong>Paciente:</strong> {{ $quality['data']['name'] ?? 'Virtual' }}</p>
                <p>•</p>
                <p><strong>Gene Afetado:</strong> <code class="bg-slate-100 px-2 py-0.5 rounded text-pink-600 font-mono">{{ $quality['data']['gene'] ?? 'N/A' }}</code></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-sm font-bold uppercase text-slate-400 mb-3">Variantes Detectadas</h3>
                    <ul class="space-y-3">
                        @if(isset($quality['data']['variants']))
                            @foreach($quality['data']['variants'] as $v)
                                <li class="flex items-start gap-3 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <div class="mt-1">
                                        <span class="block text-xs font-mono text-slate-400">{{ $v['rsid'] }}</span>
                                    </div>
                                    <div>
                                        <p class="text-slate-800 font-semibold text-sm">{{ $v['effect'] }}</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium">
                                            {{ $v['clinical_significance'] }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="bg-blue-50 rounded-xl p-6 flex flex-col justify-center">
                    <h3 class="text-blue-800 font-bold text-lg mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Questão para a Turma
                    </h3>
                    <p class="text-blue-900 italic text-lg leading-relaxed">
                        "{{ $quality['data']['teaching_question'] ?? '' }}"
                    </p>
                    <div class="mt-4 text-xs text-blue-400 uppercase font-semibold">Discussão em Sala</div>
                </div>
            </div>
        </div>
    @endif

    @endif
                <form action="/analyze" method="POST" enctype="multipart/form-data" class="mb-8">
                    @csrf
                    <div class="flex flex-col items-center gap-4 max-w-md mx-auto">
                        <label class="block w-full">
                            <span class="sr-only">Escolher arquivo</span>
                            <input type="file" name="genomic_file" required 
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-300 rounded-full bg-white shadow-sm">
                        </label>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-full transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Processar DNA
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($quality) && $quality['status'] != 'PENDING')
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 text-center">
                    <div class="text-slate-400 text-xs uppercase font-bold tracking-wider mb-1">Status</div>
                    <div class="text-xl font-black {{ ($quality['status'] ?? '') == 'APPROVED' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $quality['status'] ?? 'N/A' }}
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 text-center">
                    <div class="text-slate-400 text-xs uppercase font-bold tracking-wider mb-1">Confiabilidade</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $quality['reliability_score'] ?? 0 }}%</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 text-center">
                    <div class="text-slate-400 text-xs uppercase font-bold tracking-wider mb-1">Variantes</div>
                    <div class="text-2xl font-bold text-slate-700">{{ number_format($quality['total_variants'] ?? 0) }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-2 border-indigo-50 text-center">
                    <div class="text-indigo-400 text-xs uppercase font-bold tracking-wider mb-1">Gold Standard</div>
                    <div class="text-2xl font-bold text-indigo-700">{{ number_format($quality['gold_standard_variants'] ?? 0) }}</div>
                </div>
            </div>

            @if($mapExists)
                <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-700 relative group mb-8">
                    <div class="absolute top-4 left-4 z-10 bg-black/50 backdrop-blur-md text-white text-xs px-3 py-1 rounded-full border border-white/10">
                        🌍 Visualização Interativa
                    </div>
                    <div class="aspect-video w-full h-[600px]">
                        <iframe src="{{ asset('storage/mapa.html') }}?v={{ rand() }}" class="w-full h-full border-0"></iframe>
                    </div>
                </div>
            @endif

            @if(isset($quality['traits']) && count($quality['traits']) > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6 text-left border-b pb-2">🧬 Seus Insights Genéticos</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($quality['traits'] as $trait)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-slate-100 hover:shadow-lg transition-shadow">
                                <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex justify-between items-center">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 bg-white px-2 py-1 rounded border">{{ $trait['category'] }}</span>
                                    <span class="text-xs font-mono text-slate-400">{{ $trait['gene'] }}</span>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $trait['trait'] }}</h3>
                                    @if($trait['impact'] == 'highlight')
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 bg-indigo-100 p-1 rounded-full text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            </div>
                                            <p class="text-indigo-900 font-medium leading-tight">{{ $trait['result'] }}</p>
                                        </div>
                                    @else
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 bg-slate-100 p-1 rounded-full text-slate-500">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <p class="text-slate-600 leading-tight">{{ $trait['result'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif(isset($quality['status']) && $quality['status'] != 'PENDING')
                 <div class="mt-8 bg-yellow-50 p-4 rounded-lg border border-yellow-200 text-yellow-800">
                    <strong>Nota:</strong> Nenhuma variante de saúde conhecida foi encontrada neste arquivo de amostra. 
                 </div>
            @endif

        @endif
    </div>

    <script>
        function startProgressSimulation() {
            const panel = document.getElementById('processing-panel');
            const bar = document.getElementById('progress-bar');
            const text = document.getElementById('loading-text');
            const percentText = document.getElementById('percent-text');
            
            if(!panel) return;
            
            let progress = 0;
            const messages = ["Lendo arquivo...", "Extraindo variantes...", "Acessando Data Lake...", "Calculando PCA...", "Gerando Globo..."];
            const interval = setInterval(() => {
                if (progress < 90) {
                    progress += Math.random() * 5;
                    if (progress > 90) progress = 90;
                    bar.style.width = `${progress}%`;
                    percentText.innerText = Math.round(progress);
                    const msgIndex = Math.floor((progress / 100) * messages.length);
                    text.innerText = messages[msgIndex] || "Processando...";
                }
            }, 400);

            const checkStatus = setInterval(() => {
                fetch('/check-status').then(r => r.json()).then(data => {
                    if (data.ready) {
                        clearInterval(interval);
                        clearInterval(checkStatus);
                        bar.style.width = '100%';
                        percentText.innerText = '100';
                        text.innerText = "Concluído!";
                        bar.classList.replace('bg-indigo-600', 'bg-green-500');
                        setTimeout(() => window.location.href = "/", 1000);
                    }
                }).catch(e => console.error(e));
            }, 3000);
        }
    </script>
</body>
</html>