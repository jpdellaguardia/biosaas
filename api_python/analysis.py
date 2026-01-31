import warnings
warnings.simplefilter(action='ignore', category=FutureWarning)

import sgkit as sg
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
import time
import os

ZARR_PATH = './data_lake/processed_zarr/chr22_1000G_phase3.zarr'

def run_ancestry_analysis():
    print("🧬 Carregando o Banco de Dados Genômico (Zarr)...")
    start_time = time.time()
    
    ds = sg.load_dataset(ZARR_PATH)
    print(f"✅ Dados carregados: {ds.sizes['variants']} variantes e {ds.sizes['samples']} pessoas.")

    # 1. Thinning
    ds_subset = ds.isel(variants=slice(0, None, 10))
    print(f"🔍 Selecionadas {ds_subset.sizes['variants']} variantes iniciais.")

    # 2. O FILTRO DE QUALIDADE DEFINITIVO (CORRIGIDO)
    print("🧮 Limpando dados (Removendo variantes sem variação)...")
    ds_subset = sg.variant_stats(ds_subset)
    
    # Extrai as frequências
    af = ds_subset.variant_allele_frequency[:, 1]
    call_rate = ds_subset.variant_call_rate
    
    # REGRA TRIPLA ABSOLUTA: 
    # 1. Mais de 5% da população tem (Não é rara)
    # 2. Menos de 95% da população tem (Não é universal/fixada)
    # 3. Taxa de leitura de 100% (Sem 'NaN' ou dados faltantes)
    valid_mask = (
        (af > 0.05) & 
        (af < 0.95) & 
        (call_rate == 1.0)
    ).compute()
    
    ds_subset = ds_subset.sel(variants=valid_mask)
    
    print(f"✅ Sobraram {ds_subset.sizes['variants']} variantes com VARIAÇÃO REAL para a IA.")

    # 3. Preparação
    ds_subset = sg.count_call_alleles(ds_subset)

    # 4. Inteligência Artificial (PCA)
    print("🤖 Rodando a Inteligência Artificial (PCA)...")
    ds_pca = sg.pca(ds_subset, n_components=2, algorithm='randomized')
    ds_pca = ds_pca.compute()

    elapsed = time.time() - start_time
    print(f"✅ PCA Concluído em {elapsed:.2f} segundos!")
    return ds_pca

def plot_results(ds_pca):
    print("📊 Desenhando o gráfico de Ancestralidade...")
    pc1 = ds_pca['sample_pca_projection'].values[:, 0]
    pc2 = ds_pca['sample_pca_projection'].values[:, 1]
    
    plt.figure(figsize=(10, 6))
    plt.scatter(pc1, pc2, alpha=0.6, c='#3b82f6', s=15, edgecolors='none')
    plt.title("Mapa de Ancestralidade Genética - Cromossomo 22", fontsize=14, fontweight='bold')
    plt.xlabel("Componente Principal 1 (Variação Genética Primária)")
    plt.ylabel("Componente Principal 2 (Variação Genética Secundária)")
    plt.grid(True, linestyle='--', alpha=0.3)
    
    # Remove bordas para visual mais limpo (Web Style)
    plt.gca().spines['top'].set_visible(False)
    plt.gca().spines['right'].set_visible(False)
    
    output_image = "ancestralidade_chr22.png"
    plt.savefig(output_image, dpi=300, bbox_inches='tight')
    print(f"🎉 SUCESSO! A sua primeira análise científica está salva na imagem: {output_image}")

if __name__ == "__main__":
    if not os.path.exists(ZARR_PATH):
        print("❌ Erro: Dataset Zarr não encontrado.")
    else:
        dataset_com_pca = run_ancestry_analysis()
        plot_results(dataset_com_pca)