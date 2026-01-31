# BioSaaS - Plataforma de Análise Genômica

Uma plataforma completa para análise de dados genômicos, composta por uma API Python para processamento de dados e uma interface web Laravel.

## Estrutura do Projeto

- **api-python/**: API backend em Python para processamento de dados genômicos
- **web-laravel/**: Interface web frontend em Laravel

## Tecnologias

### Backend (Python)
- FastAPI
- sgkit (análise genômica)
- pandas, numpy
- matplotlib (visualizações)
- PostgreSQL

### Frontend (Laravel)
- Laravel Framework
- PHP
- MySQL/PostgreSQL

## Instalação

### API Python
```bash
cd api-python
pip install -r requirements.txt
uvicorn api:app --reload
```

### Web Laravel
```bash
cd web-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

## Funcionalidades

- Processamento de arquivos VCF
- Análise de ancestralidade genética
- Visualizações interativas
- Interface web intuitiva
- API RESTful para integração

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request