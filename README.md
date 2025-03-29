# Dashboard LogComex

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net)
[![FrankenPHP](https://img.shields.io/badge/FrankenPHP-6C2DC7?style=for-the-badge&logo=php&logoColor=white)](https://frankenphp.dev)
[![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)](https://vuejs.org/)
[![TypeScript](https://img.shields.io/badge/TypeScript-3178C6?style=for-the-badge&logo=typescript&logoColor=white)](https://www.typescriptlang.org/)
[![Clean Architecture](https://img.shields.io/badge/Clean-Architecture-1E90FF?style=for-the-badge&logo=clean-code&logoColor=white)](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
[![Arquitetura](https://img.shields.io/badge/Foco_em-Arquitetura_&_Escalabilidade-9400D3?style=for-the-badge&logo=blueprint&logoColor=white)](https://github.com/louisriverstone/dashboard-logcomex)

> ⚠️ **Nota sobre o desenvolvimento**: Este projeto priorizou a implementação dos componentes mais críticos e essenciais para o funcionamento da aplicação. As funcionalidades principais estão operacionais, enquanto recursos adicionais e refinamentos na interface estão planejados para futuras iterações. A arquitetura foi estruturada para facilitar a expansão contínua do sistema.

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## 🚀 Instalação e Configuração

### 1. Clone o repositório
```bash
git clone https://github.com/louisriverstone/dashboard-logcomex.git
cd dashboard-logcomex
```

### 2. Configure o ambiente
Copie o arquivo de exemplo de ambiente:
```bash
cp .env.example .env
```

### 3. Inicie os containers Docker
```bash
docker compose up -d
```

### 4. Configure a aplicação
Execute os seguintes comandos para configurar a aplicação:

```bash
# Instalando dependencias backend
docker compose exec dashboard-logcomex-app composer install

# Instalando dependencias frontend
docker compose exec dashboard-logcomex-app npm install

# Gerar uma nova chave de aplicação
docker compose exec dashboard-logcomex-app php artisan key:generate

# Executar as migrações do banco de dados
docker compose exec dashboard-logcomex-app php artisan migrate

# Carregar os dados iniciais (pode demorar devido ao volume de dados)
docker compose exec dashboard-logcomex-app php artisan db:seed

# Compilar os assets do frontend
docker compose exec dashboard-logcomex-app npm run build 
```

### 5. Inicie o servidor Octane
```bash
docker compose exec dashboard-logcomex-app php artisan octane:start --host=0.0.0.0 --port=8000
```

## 📚 Documentação da API

A documentação da API está disponível em:

```
http://localhost:8000/api/v1/docs
```

## 🧪 Testes

Execute os testes automatizados do backend com:

```bash
docker compose exec dashboard-logcomex-app php artisan test
```

Execute os testes automatizados do frontend com:

```bash
docker compose exec dashboard-logcomex-app npm run test:unit
```

## 🧪 Analizador Estático
```
docker compose exec dashboard-logcomex-app  vendor/bin/phpstan analyse --memory-limit=1g
```


## 🛠️ Desenvolvimento

Para desenvolvimento local, você pode acessar a aplicação em:

```
http://localhost:8000
```

## 📝 Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE).
