import sys
import json
import duckdb
import os

def parse_vcf(vcf_path):
    rsids = []
    try:
        with open(vcf_path, 'r') as f:
            for line in f:
                if line.startswith("#"): continue
                parts = line.split('\t')
                if len(parts) > 2:
                    rs_id = parts[2]
                    if rs_id.startswith("rs"):
                        rsids.append(rs_id)
    except Exception as e:
        print(f"Erro VCF: {e}")
    return rsids

def main():
    # Pega caminhos do Snakemake
    vcf_path = snakemake.input[0]
    output_path = snakemake.output[0]
    db_path = snakemake.params.db

    print(f"🔬 Processando: {vcf_path}")
    
    # Lógica Simples: VCF -> DuckDB -> JSON
    user_variants = parse_vcf(vcf_path)
    found_conditions = []

    if user_variants:
        try:
            con = duckdb.connect(db_path, read_only=True)
            placeholders = ','.join(['?'] * len(user_variants))
            query = f"SELECT * FROM knowledge_base WHERE rsid IN ({placeholders})"
            results = con.execute(query, user_variants).fetchall()
            con.close()

            for row in results:
                found_conditions.append({
                    "rsid": row[0],
                    "gene": row[1],
                    "condition": row[2],
                    "effect": row[4],
                    "clinical_significance": "Risk Factor"
                })
        except Exception as e:
            print(f"Erro DuckDB: {e}")

    report_data = {
        "status": "COMPLETED",
        "mode": "real_analysis",
        "matches": len(found_conditions),
        "data": {
            "name": "Usuário Teste",
            "variants": found_conditions
        }
    }

    with open(output_path, 'w') as f:
        json.dump(report_data, f, indent=4)

if __name__ == "__main__":
    main()
