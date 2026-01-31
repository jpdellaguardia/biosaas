<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfólio | Bioinformata Fullstack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-300 antialiased selection:bg-indigo-500 selection:text-white">

    <div class="max-w-screen-xl mx-auto px-6 py-12 md:px-12 md:py-20 lg:px-24 lg:py-0">
        <div class="lg:flex lg:justify-between lg:gap-4">
            
            <header class="lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-1/2 lg:flex-col lg:justify-between lg:py-24">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-100 sm:text-5xl">
                        <a href="/">Seu Nome Aqui</a>
                    </h1>
                    <h2 class="mt-3 text-lg font-medium tracking-tight text-slate-100 sm:text-xl">
                        Fullstack Developer & Bioinformata
                    </h2>
                    <p class="mt-4 max-w-xs leading-normal text-slate-400">
                        Construindo soluções acessíveis para dados genômicos complexos.
                    </p>

                    <nav class="nav hidden lg:block mt-16" aria-label="Navegação Principal">
                        <ul class="w-max">
                            
                            <li>
                                <a href="#work-samples" class="group flex items-center py-3 active">
                                    <span class="nav-indicator mr-4 h-px w-8 bg-slate-600 transition-all group-hover:w-16 group-hover:bg-indigo-400"></span>
                                    <span class="nav-text text-xs font-bold uppercase tracking-widest text-slate-500 group-hover:text-indigo-400">Work Samples</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('dashboard') }}" class="group flex items-center py-3">
                                    <span class="nav-indicator mr-4 h-px w-8 bg-slate-600 transition-all group-hover:w-16 group-hover:bg-indigo-400"></span>
                                    <span class="nav-text text-xs font-bold uppercase tracking-widest text-slate-500 group-hover:text-indigo-400">Ferramentas (BioSaaS)</span>
                                </a>
                            </li>

                            <li>
                                <a href="/curriculo.pdf" target="_blank" class="group flex items-center py-3">
                                    <span class="nav-indicator mr-4 h-px w-8 bg-slate-600 transition-all group-hover:w-16 group-hover:bg-indigo-400"></span>
                                    <span class="nav-text text-xs font-bold uppercase tracking-widest text-slate-500 group-hover:text-indigo-400">Currículo (PDF)</span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>

                <ul class="ml-1 mt-8 flex items-center" aria-label="Social media">
                    <li class="mr-5 text-xs">
                        <a href="https://github.com/SEU_USER" class="block hover:text-indigo-400" target="_blank">
                            <i class="fab fa-github text-2xl"></i>
                        </a>
                    </li>
                    <li class="mr-5 text-xs">
                        <a href="https://linkedin.com/in/SEU_USER" class="block hover:text-indigo-400" target="_blank">
                            <i class="fab fa-linkedin text-2xl"></i>
                        </a>
                    </li>
                </ul>
            </header>

            <main id="content" class="pt-24 lg:w-1/2 lg:py-24">
                
                <section id="about" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24">
                    <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-slate-200">Sobre</h2>
                    </div>
                    <div>
                        <p class="mb-4">
                            Sou um desenvolvedor apaixonado por unir 
                            <strong class="font-medium text-slate-200">Biotecnologia</strong> e 
                            <strong class="font-medium text-slate-200">Engenharia de Software</strong>. 
                            Atualmente foco no desenvolvimento de pipelines de análise genômica escaláveis usando Python e interfaces intuitivas em Laravel.
                        </p>
                        <p class="mb-4">
                            Meu objetivo é democratizar o acesso à medicina personalizada através de ferramentas SaaS (Software as a Service) que simplificam a bioinformática clínica.
                        </p>
                    </div>
                </section>

                <section id="work-samples" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24">
                    <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-slate-200">Work Samples</h2>
                    </div>
                    <ol class="group/list">
                        
                        <li class="mb-12">
                            <div class="group relative grid pb-1 transition-all sm:grid-cols-8 sm:gap-8 md:gap-4 lg:hover:!opacity-100 lg:group-hover/list:opacity-50">
                                <div class="absolute -inset-x-4 -inset-y-4 z-0 hidden rounded-md transition motion-reduce:transition-none lg:-inset-x-6 lg:block lg:group-hover:bg-slate-800/50 lg:group-hover:shadow-[inset_0_1px_0_0_rgba(148,163,184,0.1)] lg:group-hover:drop-shadow-lg"></div>
                                <header class="z-10 mb-2 mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500 sm:col-span-2">2026 - Presente</header>
                                <div class="z-10 sm:col-span-6">
                                    <h3 class="font-medium leading-snug text-slate-200">
                                        <div>
                                            <a class="inline-flex items-baseline font-medium leading-tight text-slate-200 hover:text-indigo-300 focus-visible:text-indigo-300 group/link" href="{{ route('dashboard') }}">
                                                <span class="absolute -inset-x-4 -inset-y-2.5 hidden rounded md:-inset-x-6 md:-inset-y-4 lg:block"></span>
                                                <span>BioSaaS Genômico <span class="inline-block">V3.1 <i class="fas fa-arrow-right ml-1 text-xs"></i></span></span>
                                            </a>
                                        </div>
                                    </h3>
                                    <p class="mt-2 text-sm leading-normal">
                                        Plataforma Fullstack para análise de variantes genéticas (VCF). Inclui visualização geoespacial 3D (Plotly), detecção de riscos patogênicos (DuckDB) e pipelines assíncronos (Python/FastAPI).
                                    </p>
                                    <ul class="mt-2 flex flex-wrap">
                                        <li class="mr-1.5 mt-2"><div class="flex items-center rounded-full bg-indigo-400/10 px-3 py-1 text-xs font-medium leading-5 text-indigo-300">Laravel</div></li>
                                        <li class="mr-1.5 mt-2"><div class="flex items-center rounded-full bg-indigo-400/10 px-3 py-1 text-xs font-medium leading-5 text-indigo-300">Python</div></li>
                                        <li class="mr-1.5 mt-2"><div class="flex items-center rounded-full bg-indigo-400/10 px-3 py-1 text-xs font-medium leading-5 text-indigo-300">DuckDB</div></li>
                                    </ul>
                                </div>
                            </div>
                        </li>

                        <li class="mb-12">
                            <div class="group relative grid pb-1 transition-all sm:grid-cols-8 sm:gap-8 md:gap-4 lg:hover:!opacity-100 lg:group-hover/list:opacity-50">
                                <div class="absolute -inset-x-4 -inset-y-4 z-0 hidden rounded-md transition motion-reduce:transition-none lg:-inset-x-6 lg:block lg:group-hover:bg-slate-800/50 lg:group-hover:shadow-[inset_0_1px_0_0_rgba(148,163,184,0.1)] lg:group-hover:drop-shadow-lg"></div>
                                <header class="z-10 mb-2 mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500 sm:col-span-2">2025</header>
                                <div class="z-10 sm:col-span-6">
                                    <h3 class="font-medium leading-snug text-slate-200">
                                        <div>
                                            <a class="inline-flex items-baseline font-medium leading-tight text-slate-200 hover:text-indigo-300 focus-visible:text-indigo-300 group/link" href="#">
                                                <span class="absolute -inset-x-4 -inset-y-2.5 hidden rounded md:-inset-x-6 md:-inset-y-4 lg:block"></span>
                                                <span>Pipeline de Proteômica</span>
                                            </a>
                                        </div>
                                    </h3>
                                    <p class="mt-2 text-sm leading-normal">
                                        Desenvolvimento de scripts para normalização de dados de espectrometria de massa.
                                    </p>
                                    <ul class="mt-2 flex flex-wrap">
                                        <li class="mr-1.5 mt-2"><div class="flex items-center rounded-full bg-indigo-400/10 px-3 py-1 text-xs font-medium leading-5 text-indigo-300">R</div></li>
                                        <li class="mr-1.5 mt-2"><div class="flex items-center rounded-full bg-indigo-400/10 px-3 py-1 text-xs font-medium leading-5 text-indigo-300">Docker</div></li>
                                    </ul>
                                </div>
                            </div>
                        </li>

                    </ol>
                </section>

                <section id="tools" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24">
                     <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-slate-200">Ferramentas</h2>
                    </div>
                    <div class="bg-indigo-900/20 border border-indigo-500/30 rounded-xl p-6 hover:bg-indigo-900/30 transition-all cursor-pointer group" onclick="window.location='{{ route('dashboard') }}'">
                        <h3 class="text-indigo-300 font-bold mb-2 flex items-center">
                            <i class="fas fa-dna mr-2"></i> Experimente o BioSaaS
                            <i class="fas fa-arrow-right ml-auto transform group-hover:translate-x-1 transition-transform"></i>
                        </h3>
                        <p class="text-sm text-slate-400">
                            Acesse a ferramenta completa de análise de arquivos VCF. Faça upload do seu DNA e veja a análise de ancestralidade 3D e riscos de saúde.
                        </p>
                    </div>
                </section>
                
                <footer class="max-w-md pb-16 text-sm text-slate-500 sm:pb-0">
                    <p>
                        Desenvolvido com <a href="https://laravel.com" class="font-medium text-slate-400 hover:text-indigo-300">Laravel</a> e 
                        <a href="https://fastapi.tiangolo.com/" class="font-medium text-slate-400 hover:text-indigo-300">FastAPI</a>.
                        Inspirado no design de <a href="https://tnisenbaum.github.io/" target="_blank" class="font-medium text-slate-400 hover:text-indigo-300">T. Nisenbaum</a>.
                    </p>
                </footer>

            </main>
        </div>
    </div>

</body>
</html>