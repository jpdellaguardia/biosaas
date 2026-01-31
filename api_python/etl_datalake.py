import duckdb
import pandas as pd
import os

# Caminhos
DB_PATH = 'data_lake/genomic_catalog.db'
METADATA_FILE = 'metadados.tsv'

# 1. Verificação de Arquivo
if not os.path.exists(METADATA_FILE):
    print("❌ Erro: O arquivo 'metadados.tsv' não existe.")
    exit(1)

print("⏳ Lendo arquivo com Pandas (Motor Inteligente)...")

try:
    # 2. Leitura Inteligente com Pandas
    # sep='\t' força tabulação, que é o padrão oficial desse arquivo
    df = pd.read_csv(METADATA_FILE, sep='\t')
    
    # Remove colunas vazias estranhas (como aquelas 'column4', 'column5' que apareceram)
    df = df.dropna(axis=1, how='all')
    
    print(f"✅ Pandas leu {len(df)} linhas com sucesso.")
    print(f"   Colunas encontradas: {list(df.columns)}")

    # 3. Normalização dos Nomes (Padronização para Inglês Técnico)
    rename_map = {
        'sample': 'sample_id',
        'Sample': 'sample_id',
        'pop': 'population_code',
        'super_pop': 'region_code',
        'gender': 'sex'
    }
    df = df.rename(columns=rename_map)
    
    # Mantemos apenas as colunas que importam para o negócio
    cols_to_keep = ['sample_id', 'population_code', 'region_code', 'sex']
    # Filtra apenas se as colunas existirem no arquivo
    df_final = df[[c for c in cols_to_keep if c in df.columns]]

    print(f"🌊 Salvando no Lakehouse ({DB_PATH})...")
    
    # 4. Ingestão no DuckDB
    con = duckdb.connect(database=DB_PATH, read_only=False)
    
    # O DuckDB consegue ler direto do DataFrame do Pandas (Mágica!)
    con.execute("CREATE OR REPLACE TABLE populacoes AS SELECT * FROM df_final")
    
    # 5. Validação Final
    total = con.execute("SELECT COUNT(*) FROM populacoes").fetchone()[0]
    
    if total > 0:
        print(f"🚀 SUCESSO TOTAL! Catálogo Genômico atualizado com {total} registros.")
        print("\n📊 Amostra dos Dados:")
        print(con.execute("SELECT * FROM populacoes LIMIT 3").df())
    else:
        print("⚠️ ALERTA: A tabela foi criada, mas continua vazia. O arquivo original pode estar em branco.")
    
    con.close()

except Exception as e:
    print(f"\n❌ ERRO CRÍTICO: {e}")
    print("Dica: Se o erro persistir, o arquivo metadados.tsv pode estar corrompido.")