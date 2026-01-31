import sgkit as sg
from sgkit.io.vcf import vcf_to_zarr
import os
import psycopg2
import time

# --- CONFIGURAÇÕES ---
# Onde está o ficheiro que descarregámos
VCF_PATH = './data_lake/raw_vcf/chr22_1000G_phase3.vcf.gz'

# Onde vamos guardar o novo formato super-rápido
ZARR_DIR = './data_lake/processed_zarr/'
ZARR_PATH = os.path.join(ZARR_DIR, 'chr22_1000G_phase3.zarr')

PG_CREDENTIALS = "dbname=genatlas_db user=postgres password=root host=localhost"

# Criar a pasta de destino
os.makedirs(ZARR_DIR, exist_ok=True)

def transform_vcf_to_zarr():
    print(f"🚀 Iniciando a Alquimia de Dados: VCF -> Zarr...")
    print(f"Lendo: {VCF_PATH}")
    start_time = time.time()
    
    # O comando mágico (adaptado para o sgkit v0.3.0)
    vcf_to_zarr(
        VCF_PATH,
        ZARR_PATH,
        chunk_length=5000, # Quantas variantes (linhas) ler por vez
        chunk_width=1000   # Quantas amostras (colunas) processar por vez
    )
    
    elapsed = time.time() - start_time
    print(f"✅ Conversão concluída em {elapsed:.2f} segundos!")

def update_database():
    conn = psycopg2.connect(PG_CREDENTIALS)
    cur = conn.cursor()
    # Atualizamos o banco dizendo: "O ETL rodou e os dados estão prontos"
    query = """
    UPDATE genomic_reference_files 
    SET is_processed = TRUE 
    WHERE s3_path = %s
    """
    cur.execute(query, (VCF_PATH,))
    conn.commit()
    cur.close()
    conn.close()
    print("✅ PostgreSQL atualizado: Status alterado para is_processed = TRUE.")

if __name__ == "__main__":
    if not os.path.exists(VCF_PATH):
        print("❌ Erro: O arquivo VCF não foi encontrado. Rode o ingestion.py primeiro.")
    else:
        transform_vcf_to_zarr()
        update_database()
        print(f"🎉 Sucesso! O seu Dataset Zarr Cloud-Native está pronto em: {ZARR_PATH}")