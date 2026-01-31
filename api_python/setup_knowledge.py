import duckdb

# Conecta ao banco existente
con = duckdb.connect('./data_lake/genomic_catalog.db')

# 1. Cria a tabela
con.execute("""
CREATE TABLE IF NOT EXISTS knowledge_base (
    rsid VARCHAR,
    gene VARCHAR,
    trait VARCHAR,
    risk_allele VARCHAR,
    description_risk VARCHAR,
    description_non_risk VARCHAR,
    category VARCHAR
);
""")

# 2. Insere dados reais (Exemplos científicos)
knowledge_data = [
    # Lactose (O famoso LCT)
    ('rs4988235', 'LCT', 'Tolerância à Lactose', 'C', 
     'Provável Intolerância à Lactose (Adulto)', 
     'Persistência da Lactase (Pode beber leite)', 
     'Nutrição'),
    
    # Cera de Ouvido / Odor (ABCC11)
    ('rs17822931', 'ABCC11', 'Tipo de Cera de Ouvido', 'T', 
     'Cera de ouvido seca, menor odor corporal (Comum em asiáticos)', 
     'Cera de ouvido úmida, odor corporal normal', 
     'Aparência'),
     
    # Cafeína (CYP1A2)
    ('rs762551', 'CYP1A2', 'Metabolismo de Cafeína', 'C', 
     'Metabolizador Lento (Cafeína pode causar ansiedade/insônia)', 
     'Metabolizador Rápido', 
     'Nutrição'),

    # Olhos Azuis (HERC2)
    ('rs12913832', 'HERC2', 'Cor dos Olhos', 'G', 
     'Maior probabilidade de olhos azuis ou verdes', 
     'Maior probabilidade de olhos castanhos', 
     'Aparência')
]

con.executemany("INSERT INTO knowledge_base VALUES (?, ?, ?, ?, ?, ?, ?)", knowledge_data)

print("✅ Base de Conhecimento Genômico atualizada com sucesso!")
con.close()