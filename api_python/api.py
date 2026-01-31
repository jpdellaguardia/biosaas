from fastapi import FastAPI, BackgroundTasks
from pydantic import BaseModel
import os
import json
import time
import random
import subprocess  # <--- BIBLIOTECA PARA RODAR COMANDOS DO TERMINAL
import matplotlib
matplotlib.use('Agg') 
import matplotlib.pyplot as plt
import traceback

# Importa seus casos clínicos demo
from clinical_cases import CLINICAL_CASES

app = FastAPI()

class AnalysisRequest(BaseModel):
    file_path: str

def run_genomic_pipeline(file_path: str):
    print(f"🧬 [API] Iniciando processamento para: {file_path}")
    
    try:
        # --- 1. CONFIGURAÇÃO DE CAMINHOS ---
        base_dir = os.path.dirname(os.path.abspath(__file__))
        
        # Caminhos do Laravel (Output)
        # Tenta achar a pasta com traço ou underline
        path_dash = os.path.join(base_dir, "../web-laravel/storage/app/public")
        path_underscore = os.path.join(base_dir, "../web_laravel/storage/app/public")
        
        if os.path.exists(os.path.join(base_dir, "../web-laravel")):
            output_dir = path_dash
        elif os.path.exists(os.path.join(base_dir, "../web_laravel")):
            output_dir = path_underscore
        else:
            output_dir = path_dash # Default
            os.makedirs(output_dir, exist_ok=True)

        map_file = os.path.join(output_dir, "mapa.html")
        report_file = os.path.join(output_dir, "quality_report.json")
        chart_file = os.path.join(output_dir, "ancestralidade_chr22.png")
        
        # Caminhos do Snakemake (Input/Resources)
        snakefile_path = os.path.join(base_dir, "pipeline/Snakefile")
        db_path = os.path.join(base_dir, "data_lake/genomic_catalog.db")
        
        # --- 2. MODO DEMO (Mantém igual, pois é didático) ---
        if file_path.startswith("DEMO:"):
            print("📘 [API] Modo Demo detectado.")
            key = file_path.replace(":", "_")
            
            if key in CLINICAL_CASES:
                time.sleep(2)
                case_data = CLINICAL_CASES[key]
                
                # Gera JSON
                report_content = {
                    "status": "COMPLETED",
                    "mode": "demo_case",
                    "data": case_data,
                    "reliability_score": 100
                }
                with open(report_file, "w") as f:
                    json.dump(report_content, f)
                
                # Gera Mapa Dummy
                with open(map_file, "w") as f:
                    f.write(f"<h1>Mapa Demo: {case_data['condition']}</h1>")
                
                print("✅ [API] Demo concluída.")
                return

        # --- 3. MODO REAL (CHAMADA DO SNAKEMAKE) 🐍 ---
        print("🚀 [API] Invocando Snakemake para análise real...")
        
        # Monta o comando do Snakemake dinamicamente
        # Equivalente a: snakemake --cores 1 --config input=... output=...
        cmd = [
            "snakemake",
            "--snakefile", snakefile_path,
            "--cores", "1",
            "--config",
            f"input_vcf={file_path}",
            f"output_json={report_file}",
            f"db_path={db_path}"
        ]
        
        # Executa o comando e captura logs
        # cwd=base_dir garante que ele rode a partir da pasta api_python
        result = subprocess.run(
            cmd, 
            cwd=base_dir,
            capture_output=True,
            text=True
        )

        # Verifica se o Snakemake rodou bem
        if result.returncode == 0:
            print("✅ [Snakemake] Pipeline finalizado com sucesso!")
            
            # Gera o mapa 3D (Se o Snakemake ainda não gerou, geramos um placeholder)
            # No futuro, o Snakemake também vai gerar esse HTML
            with open(map_file, "w") as f:
                f.write("<h1>Análise Real Concluída via Snakemake</h1><p>Veja os resultados no painel.</p>")
                
            # Gera gráfico estático (Placeholder)
            plt.figure(figsize=(10, 6))
            plt.scatter([random.gauss(0, 1) for _ in range(300)], 
                        [random.gauss(0, 1) for _ in range(300)], 
                        alpha=0.6, c='#3b82f6', s=15, edgecolors='none')
            plt.title("Mapa de Ancestralidade Genética (Simulação)", fontsize=14, fontweight='bold')
            plt.xlabel("Componente Principal 1 (Variação Genética Primária)")
            plt.ylabel("Componente Principal 2 (Variação Genética Secundária)")
            plt.grid(True, linestyle='--', alpha=0.3)
            
            # Remove bordas para visual mais limpo (Web Style)
            plt.gca().spines['top'].set_visible(False)
            plt.gca().spines['right'].set_visible(False)
            
            plt.savefig(chart_file, dpi=300, bbox_inches='tight')
            plt.close()
            
        else:
            print("❌ [Snakemake] Erro na execução:")
            print(result.stderr) # Imprime o erro do Snakemake no terminal
            
            # Salva um JSON de erro para o Laravel mostrar
            error_content = {
                "status": "ERROR",
                "message": "Falha no processamento do VCF.",
                "debug": result.stderr[:200]
            }
            with open(report_file, "w") as f:
                json.dump(error_content, f)

    except Exception as e:
        print(f"❌ [API] Erro Grave: {e}")
        traceback.print_exc()

@app.post("/api/v1/run-ancestry")
async def start_analysis(request: AnalysisRequest, background_tasks: BackgroundTasks):
    background_tasks.add_task(run_genomic_pipeline, request.file_path)
    return {"message": "Pipeline Iniciado"}

@app.get("/")
def read_root():
    return {"status": "BioSaaS Python API is running 🚀"}