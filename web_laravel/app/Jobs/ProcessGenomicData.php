<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProcessGenomicData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    protected $dnaFilePath; // Variável para guardar o caminho do arquivo

    // O Trabalhador agora recebe o arquivo quando é criado
    public function __construct($dnaFilePath)
    {
        $this->dnaFilePath = $dnaFilePath;
    }

    public function handle(): void
    {
        // 1. O Trabalhador envia o CAMINHO DO ARQUIVO para o Python via POST
        $response = Http::timeout(100)->post('http://127.0.0.1:8000/api/v1/run-ancestry', [
            'file_path' => $this->dnaFilePath
        ]);

        if ($response->successful()) {
            // 2. Salva a imagem gerada
            Storage::disk('public')->put('ancestralidade.png', $response->body());
        }
    }
}