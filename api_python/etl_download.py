import os
import sys
import boto3
from botocore import UNSIGNED
from botocore.config import Config
from urllib.parse import urlparse

# --- CONFIGURAÇÃO ---
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DOWNLOAD_DIR = os.path.join(BASE_DIR, "../web_laravel/storage/app/private/dna_uploads")
os.makedirs(DOWNLOAD_DIR, exist_ok=True)

def download_s3_custom(s3_link):
    """
    Recebe um link s3://completo e faz o download
    """
    # Limpa espaços em branco que podem vir no copy-paste
    s3_link = s3_link.strip()
    
    # Validação simples
    if not s3_link.startswith("s3://"):
        print("\n❌ Erro: O link deve começar com 's3://'")
        return

    # Parse automático (Separa Bucket de Key)
    # Ex: s3://1000genomes/pasta/arquivo -> bucket="1000genomes", key="pasta/arquivo"
    parsed = urlparse(s3_link)
    bucket_name = parsed.netloc
    file_key = parsed.path.lstrip('/') # Remove a barra inicial

    filename = os.path.basename(file_key)
    output_path = os.path.join(DOWNLOAD_DIR, filename)
    
    print(f"\n☁️  Bucket: {bucket_name}")
    print(f"📄 Arquivo: {file_key}")
    print(f"📂 Salvando em: {output_path}")
    
    # Cliente S3 Público
    s3 = boto3.client('s3', config=Config(signature_version=UNSIGNED))
    
    try:
        def progress(bytes_transferred):
            sys.stdout.write(f"\r🚀 Baixando... {bytes_transferred/1024/1024:.2f} MB")
            sys.stdout.flush()

        print("⏳ Iniciando...")
        s3.download_file(bucket_name, file_key, output_path, Callback=progress)
        print(f"\n\n✅ SUCESSO! Download concluído.")
    except Exception as e:
        print(f"\n❌ Falha no download: {e}")
        print("Dica: Verifique se o nome do arquivo está 100% correto.")

if __name__ == "__main__":
    print("\n🧬 BIOSAAS DOWNLOADER - AWS S3 🧬")
    print("---------------------------------------")
    print("[1] 1000 Genomes (Exemplo Rápido - Chr22)")
    print("[2] 1000 Genomes (Exemplo Anemia - Chr11)")
    print("[3] 👉 COLAR LINK MANUALMENTE (s3://...)")
    print("---------------------------------------")
    
    escolha = input("Escolha uma opção: ").strip()

    if escolha == "1":
        link = "s3://1000genomes/release/20130502/ALL.chr22.phase3_shapeit2_mvncall_integrated_v5a.20130502.genotypes.vcf.gz"
        download_s3_custom(link)
    
    elif escolha == "2":
        link = "s3://1000genomes/release/20130502/ALL.chr11.phase3_shapeit2_mvncall_integrated_v5a.20130502.genotypes.vcf.gz"
        download_s3_custom(link)

    elif escolha == "3":
        print("\nCole o link S3 completo abaixo:")
        print("Exemplo: s3://1000genomes/pasta/arquivo.vcf")
        custom_link = input(">> ")
        download_s3_custom(custom_link)
        
    else:
        print("Opção inválida.")