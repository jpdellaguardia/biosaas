import duckdb

DB_PATH = './data_lake/genomic_catalog.db'

print("🚀 Iniciando população robusta do Banco de Conhecimento...")

con = duckdb.connect(database=DB_PATH)

# 1. Limpa a tabela antiga para recomeçar do zero e sem duplicatas
con.execute("DROP TABLE IF EXISTS knowledge_base")
con.execute("""
CREATE TABLE knowledge_base (
    rsid VARCHAR,
    gene VARCHAR,
    trait VARCHAR,
    risk_allele VARCHAR,
    description_risk VARCHAR,
    description_non_risk VARCHAR,
    category VARCHAR
);
""")

# 2. Lista Curada de Variantes de Alta Relevância Clínica (ACMG / ClinVar)
# Formato: (rsID, Gene, Traço, Alelo Risco, Descrição Positiva, Descrição Negativa, Categoria)

medical_data = [
    # --- CÂNCER HEREDITÁRIO ---
    ('rs1799966', 'BRCA1', 'Câncer de Mama/Ovário', 'C', 
     'Variante S1613G detectada (Risco Aumentado)', 'Ausência desta variante comum', 'Oncologia'),
    ('rs144848', 'BRCA2', 'Câncer de Mama', 'T', 
     'Variante N372H associada a risco aumentado', 'Ausência de variante de risco N372H', 'Oncologia'),

    # --- SAÚDE CARDIOVASCULAR E SANGUE ---
    ('rs6025', 'F5', 'Trombofilia (Fator V Leiden)', 'A', 
     'Risco aumentado de trombose (Coágulos)', 'Coagulação normal', 'Cardiologia'),
    ('rs1799963', 'F2', 'Protrombina (G20210A)', 'A', 
     'Risco elevado de trombose venosa', 'Risco normal de trombose', 'Cardiologia'),
    ('rs1801133', 'MTHFR', 'Metabolismo de Folato', 'A', 
     'Atividade enzimática reduzida (Risco de homocisteína alta)', 'Metabolismo de folato normal', 'Nutrição/Sangue'),

    # --- DOENÇAS METABÓLICAS ---
    ('rs1800562', 'HFE', 'Hemocromatose (C282Y)', 'A', 
     'Risco alto de acúmulo de ferro no sangue', 'Absorção de ferro normal', 'Metabolismo'),
    ('rs1799945', 'HFE', 'Hemocromatose (H63D)', 'G', 
     'Risco moderado de sobrecarga de ferro', 'Variante H63D ausente', 'Metabolismo'),
    ('rs429358', 'APOE', 'Doença de Alzheimer (E4)', 'C', 
     'Alelo E4 presente: Risco significativamente aumentado de Alzheimer tardio', 'Ausência do alelo E4 de alto risco', 'Neurologia'),

    # --- PORTADOR DE DOENÇAS RECESSIVAS (Carrier Status) ---
    ('rs113993960', 'CFTR', 'Fibrose Cística (DeltaF508)', 'C', 
     'PORTADOR: Variante DeltaF508 detectada. Importante para planejamento familiar.', 'Não portador da mutação DeltaF508', 'Planejamento Familiar'),
    ('rs334', 'HBB', 'Anemia Falciforme', 'T', 
     'Traço Falciforme detectado (Portador)', 'Hemoglobina normal', 'Sangue'),

    # --- FARMACOGENÔMICA (Resposta a Remédios) ---
    ('rs762551', 'CYP1A2', 'Metabolismo de Cafeína', 'C', 
     'Metabolizador Lento: Cafeína causa mais ansiedade e dura mais tempo', 'Metabolizador Rápido', 'Farmacologia'),
    ('rs4149056', 'SLCO1B1', 'Risco de Miopatia por Estatinas', 'C', 
     'Risco aumentado de dor muscular ao tomar sinvastatina', 'Tolerância normal a estatinas', 'Farmacologia'),
    ('rs9923231', 'VKORC1', 'Sensibilidade a Varfarina', 'T', 
     'Alta sensibilidade (Necessita dose menor de anticoagulante)', 'Resposta padrão a Varfarina', 'Farmacologia'),

    # --- TRAÇOS CURIOSOS E BEM-ESTAR ---
    ('rs4988235', 'LCT', 'Tolerância à Lactose', 'C', 
     'Intolerância à Lactose (Genótipo Adulto)', 'Persistência da Lactase (Pode beber leite)', 'Nutrição'),
    ('rs1800497', 'DRD2', 'Receptores de Dopamina', 'T', 
     'Densidade reduzida (Busca por novidade/prazer, risco de vícios)', 'Densidade normal', 'Comportamento'),
    ('rs17822931', 'ABCC11', 'Odor Corporal', 'T', 
     'Cera de ouvido seca e pouco odor corporal (Genética Asiática)', 'Cera úmida e odor normal', 'Aparência'),
    ('rs12913832', 'HERC2', 'Cor dos Olhos', 'G', 
     'Predisposição para olhos claros (Azul/Verde)', 'Predisposição para olhos castanhos', 'Aparência'),
    ('rs4680', 'COMT', 'Resposta ao Estresse', 'A', 
     'Guerreiro: Melhor performance sob pressão, menor tolerância à dor', 'Worrier: Melhor memória e atenção, pior sob estresse', 'Cognitivo')
]

try:
    con.executemany("INSERT INTO knowledge_base VALUES (?, ?, ?, ?, ?, ?, ?)", medical_data)
    print(f"✅ Sucesso! {len(medical_data)} condições clínicas adicionadas ao banco.")
    
    # Validação rápida
    count = con.execute("SELECT COUNT(*) FROM knowledge_base").fetchone()[0]
    print(f"📊 Total de variantes monitoradas agora: {count}")
    
except Exception as e:
    print(f"❌ Erro ao popular banco: {e}")

con.close()
