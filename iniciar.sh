#!/bin/bash

# Cores para facilitar a leitura
VERDE='\033[0;32m'
AZUL='\033[0;34m'
NC='\033[0m' # Sem cor

echo -e "${AZUL}🚀 Iniciando BioSaaS Fullstack...${NC}"

# Função para matar os processos ao sair (Ctrl+C)
cleanup() {
    echo -e "\n${AZUL}🛑 Encerrando todos os serviços...${NC}"
    kill $API_PID
    kill $QUEUE_PID
    kill $SERVE_PID
    exit
}

# Captura o sinal de interrupção (Ctrl+C)
trap cleanup SIGINT

# --- 1. API PYTHON ---
echo -e "${VERDE}🐍 [1/3] Iniciando API Python (Port 8000)...${NC}"
cd api_python
source venv/bin/activate
# Roda em background (&) e salva o ID do processo ($!)
uvicorn api:app --host 0.0.0.0 --port 8000 --reload &
API_PID=$!
cd ..

# --- 2. LARAVEL QUEUE ---
echo -e "${VERDE}🐘 [2/3] Iniciando Fila Laravel...${NC}"
cd web_laravel
php artisan queue:work &
QUEUE_PID=$!

# --- 3. LARAVEL SERVE ---
echo -e "${VERDE}🐘 [3/3] Iniciando Servidor Laravel (Port 8080)...${NC}"
php artisan serve --port=8080 &
SERVE_PID=$!
cd ..

echo -e "${AZUL}✅ Tudo rodando! Acesse http://localhost:8080${NC}"
echo -e "${AZUL}Pressione Ctrl+C para parar tudo.${NC}"

# Espera os processos rodarem
wait
