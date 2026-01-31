# BIOSAS - Análise de Ancestralidade Genética

Sistema de análise genômica para estudo de ancestralidade usando dados do projeto 1000 Genomes.
Repositório de ferramentas de bioinformática para análise de variação genética populacional.

## 🧬 Funcionalidades

- **Ingestão de Dados**: Download automático de dados genômicos do cromossomo 22
- **Processamento**: Conversão de VCF para formato Zarr otimizado
- **Análise**: PCA para mapeamento de ancestralidade genética
- **Visualização**: Gráficos de componentes principais

## 🚀 Como Usar

1. **Ingestão dos dados**:
```bash
python ingestion.py
```

2. **Processamento**:
```bash
python processing.py
```

3. **Análise**:
```bash
python analysis.py
```

## 📊 Resultado

O sistema gera um gráfico `ancestralidade_chr22.png` mostrando o mapa de ancestralidade genética baseado nos componentes principais.

## 🛠️ Dependências

- sgkit
- matplotlib
- psycopg2
- requests
- tqdm

## 📁 Estrutura

```
biosas/
├── data_lake/
│   ├── raw_vcf/          # Dados VCF originais
│   └── processed_zarr/   # Dados processados em Zarr
├── ingestion.py          # Download de dados
├── processing.py         # Conversão VCF → Zarr
└── analysis.py          # Análise PCA e visualização
```
