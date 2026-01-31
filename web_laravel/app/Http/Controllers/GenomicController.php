<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // Importante para conectar com o Python

class GenomicController extends Controller
{
    /**
     * Exibe o Dashboard (Tela Principal do App)
     * Carrega os resultados se eles existirem.
     */
    public function index()
    {
        // 1. Estrutura padrão (caso não tenha análise ainda)
        $qualityReport = [
            'total_variants' => 0,
            'gold_standard_variants' => 0,
            'reliability_score' => 0,
            'status' => 'PENDING'
        ];

        // 2. Tenta ler o Relatório JSON gerado pelo Python
        if (Storage::disk('public')->exists('quality_report.json')) {
            $jsonContent = Storage::disk('public')->get('quality_report.json');
            // O "true" transforma o JSON em array associativo do PHP
            $qualityReport = json_decode($jsonContent, true);
        }

        // 3. Verifica se o mapa 3D já foi criado
        $mapExists = Storage::disk('public')->exists('mapa.html');

        // 4. Envia tudo para a view (dashboard.blade.php)
        return view('dashboard', [
            'quality' => $qualityReport,
            'mapExists' => $mapExists
        ]);
    }

    /**
     * Processa o envio de dados (Upload ou Demo)
     */
    public function upload(Request $request) 
    {
       // --- CENÁRIO A: MODO DEMO (Estudante/Professor) ---
       // Se o usuário clicou nos botões de "Caso Clínico" em vez de upar arquivo
       if ($request->has('demo_case')) {
           
           // Limpa resultados anteriores para não misturar
           Storage::disk('public')->delete(['mapa.html', 'quality_report.json']);

           $caseType = $request->input('demo_case'); // Ex: 'SICKLE_CELL' ou 'WARFARIN'
           
           // Envia para o Python avisando que é uma DEMO
           try {
               Http::timeout(1)->post('http://127.0.0.1:8000/api/v1/run-ancestry', [
                   'file_path' => "DEMO:" . $caseType 
               ]);
           } catch (\Exception $e) { 
               // Timeout esperado (Fire and Forget)
           }

           return redirect()->route('dashboard')
               ->with('status', '📚 Carregando Caso Clínico Didático...');
       }

       // --- CENÁRIO B: UPLOAD DE ARQUIVO REAL ---
       // 1. Validação (Só aceita se tiver arquivo)
       $request->validate([
           'genomic_file' => 'required|file|max:50000' // Max 50MB
       ]);

       // 2. Limpar resultados anteriores
       Storage::disk('public')->delete(['mapa.html', 'quality_report.json']);

       // 3. Salvar o arquivo na pasta privada
       $path = $request->file('genomic_file')->store('dna_uploads');
       $absolutePath = storage_path('app/private/' . $path);

       // 4. Acionar o Python (API)
       try {
           // Timeout curto (1s) para não travar o navegador do usuário.
           // O Python continua processando em segundo plano.
           Http::timeout(1)->post('http://127.0.0.1:8000/api/v1/run-ancestry', [
               'file_path' => $absolutePath
           ]);
       } catch (\Exception $e) {
           // Ignoramos erro de timeout, pois é intencional.
       }

       // 5. Redirecionar com mensagem de sucesso
       return redirect()->route('dashboard')
           ->with('status', '🧬 DNA enviado! Iniciando análise...');
    }
}