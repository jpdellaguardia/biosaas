import requests
import psycopg2
import os
from tqdm import tqdm

# --- CONFIGURAÇÕES: FONTE OFICIAL EUROPEIA (EBI) ---
# A URL correta com a versão "v5a" para o Chr22
# --- CONFIGURAÇÕES: FONTE FEDERAL (NIH - EUA) ---
FILE_URL = 'https://ftp-trace.ncbi.nih.gov/1000genomes/ftp/release/20130502/ALL.chr22.phase3_shapeit2_mvncall_integrated_v5a.20130502.genotypes.vcf.gz'
FILE_NAME = 'chr22_1000G_phase3.vcf.gz'

LOCAL_DIR = './data_lake/raw_vcf/'
PG_CREDENTIALS = "dbname=genatlas_db user=postgres password=root host=localhost"

os.makedirs(LOCAL_DIR, exist_ok=True)

def download_http_ebi(url, local_path):
    print(f"Conectando ao Instituto Europeu de Bioinformática (EBI)...")
    
    # Requisição HTTP padrão (stream para não sobrecarregar a memória)
    response = requests.get(url, stream=True)
    response.raise_for_status() # Falha imediatamente se o link estiver quebrado
    
    file_size = int(response.headers.get('content-length', 0))
    print(f"Arquivo localizado. Tamanho: {file_size / (1024**2):.2f} MB")
    
    # Download com barra de progresso
    with open(local_path, 'wb') as file, tqdm(
        desc=FILE_NAME,
        total=file_size,
        unit='B',
        unit_scale=True,
        unit_divisor=1024,
    ) as bar:
        for chunk in response.iter_content(chunk_size=8192):
            file.write(chunk)
            bar.update(len(chunk))
            
    return file_size / (1024 ** 3) # Retorna em GB para o banco

def register_in_database(file_size_gb, local_path):
    conn = psycopg2.connect(PG_CREDENTIALS)
    cur = conn.cursor()
    query = """
    INSERT INTO genomic_reference_files 
    (project_name, chromosome, file_type, s3_path, file_size_gb) 
    VALUES (%s, %s, %s, %s, %s)
    """
    cur.execute(query, ('1000 Genomes Phase 3', 'chr22', 'vcf.gz', local_path, file_size_gb))
    conn.commit()
    cur.close()
    conn.close()
    print("✅ Registro inserido no PostgreSQL com sucesso!")

# --- EXECUÇÃO ---
if __name__ == "__main__":
    local_path = os.path.join(LOCAL_DIR, FILE_NAME)
    size_gb = download_http_ebi(FILE_URL, local_path)
    register_in_database(size_gb, local_path)
    print(f"🎉 Download concluído com sucesso!")