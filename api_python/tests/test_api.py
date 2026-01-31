from fastapi.testclient import TestClient
from unittest.mock import patch
import sys
import os

# Adiciona o diretório pai ao path para conseguir importar o 'api.py'
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from api import app, analyze_user_upload

client = TestClient(app)

# --- TESTES UNITÁRIOS (Testam funções isoladas) ---

def test_analyze_user_upload_arquivo_pequeno(tmp_path):
    """Testa se a função de contagem de linhas funciona para arquivos pequenos"""
    # 1. Cria um arquivo falso temporário
    d = tmp_path / "dna_fake"
    d.mkdir()
    arquivo_teste = d / "meu_dna.txt"
    arquivo_teste.write_text("linha1\nlinha2\nlinha3\n") # 3 linhas

    # 2. Chama a função real
    resultado = analyze_user_upload(str(arquivo_teste))

    # 3. Verifica se a matemática bateu
    assert resultado['total_variants'] == 3
    assert resultado['status'] == "LOW_COVERAGE"

def test_analyze_user_upload_ignora_comentarios(tmp_path):
    """Testa se a função ignora linhas que começam com #"""
    d = tmp_path / "dna_fake"
    d.mkdir()
    arquivo_teste = d / "vcf_real.txt"
    conteudo = """##fileformat=VCFv4.2
#CHROM POS ID REF ALT
chr1 100 . A T .
chr1 101 . G C .
"""
    arquivo_teste.write_text(conteudo)

    resultado = analyze_user_upload(str(arquivo_teste))
    
    # Deve contar apenas as 2 linhas de dados, ignorando as 2 de cabeçalho
    assert resultado['total_variants'] == 2

# --- TESTES DE INTEGRAÇÃO (Testam a API completa) ---

@patch("api.process_genomics_and_map") # <--- A MÁGICA DO MOCK
@patch("api.generate_interactive_map")
def test_endpoint_run_ancestry(mock_map, mock_process):
    """
    Testa a rota /api/v1/run-ancestry.
    NÃO roda o PCA real. Finge que o PCA retornou sucesso.
    """
    
    # 1. Configura o "Fingimento" (Mock Return)
    # Quando o código chamar process_genomics_and_map, retorne isso imediatamente:
    mock_process.return_value = (None, {"reliability_score": 99.9, "status": "TEST_OK"})
    
    # Quando chamar generate_interactive_map, retorne um caminho falso
    # Cria um arquivo HTML falso para o teste ler
    with open("teste_mapa.html", "w") as f:
        f.write("<html>Globo 3D</html>")
    mock_map.return_value = "teste_mapa.html"

    # 2. Faz a requisição POST para a API
    payload = {"file_path": "caminho/qualquer/arquivo.txt"}
    response = client.post("/api/v1/run-ancestry", json=payload)

    # 3. Validações (Assertions)
    assert response.status_code == 200 # Tem que dar sucesso
    data = response.json()
    assert "map_html" in data
    assert "quality_report" in data
    assert data["quality_report"]["reliability_score"] == 99.9
    
    # Limpeza
    if os.path.exists("teste_mapa.html"):
        os.remove("teste_mapa.html")