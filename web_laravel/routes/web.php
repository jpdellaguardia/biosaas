<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\GenomicController;

// 1. Rota Raiz = PORTFÓLIO
// Quando alguém acessa o site, vê sua apresentação profissional
Route::get('/', function () {
    return view('portfolio');
});

// 2. Rota do Sistema = DASHBOARD
// Onde acontece o upload e a visualização dos resultados
// O 'name' serve para podermos redirecionar para cá facilmente pelo Controller
Route::get('/app', [GenomicController::class, 'index'])->name('dashboard');

// 3. Rota de Ação = PROCESSAMENTO
// Recebe o arquivo do formulário e manda para o Python
// OBS: Verifique se no seu GenomicController o método chama 'upload' ou 'analyze'
Route::post('/analyze', [GenomicController::class, 'upload']);

// 4. Rota "Espião" (API Interna)
// O JavaScript chama isso a cada 3 segundos para saber se o Python acabou
Route::get('/check-status', function () {
    // Verifica se o arquivo final (mapa.html) já existe na pasta pública
    $exists = Storage::disk('public')->exists('mapa.html');
    
    return response()->json(['ready' => $exists]);
});