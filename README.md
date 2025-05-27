
# Sistema de Licenciamento

Sistema para gerenciar licenças de software, notas fiscais e inventário, desenvolvido em Laravel.

![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![Status](https://img.shields.io/badge/status-active-brightgreen)

---

## ✅ Funcionalidades

- Gestão de Licenças
- Controle de Notas Fiscais com upload de PDF
- Inventário de Recursos
- Backups e Restauração do Banco de Dados
- Painel de Administração com abas
- Configurações: Logo, Fuso Horário, Segurança, Sistema
- Logs e Relatórios

---

## 🚀 Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/seuusuario/licencas.git
cd licencas
```

### 2. Torne o script de instalação executável

```bash
chmod +x install.sh
```

### 3. Execute a instalação automática

```bash
./install.sh
```

Isso irá:  
✅ Instalar dependências PHP e JS  
✅ Configurar `.env` e gerar chave  
✅ Rodar migrations e seeders  
✅ Criar link simbólico `storage`  
✅ Limpar cache de configuração  

---

## ⚙️ Configuração do Ambiente (.env)

Após clonar o repositório, copie o arquivo de exemplo `.env.example` para `.env`:

```bash
cp .env.example .env
```

Em seguida, gere a chave de segurança da aplicação:

```bash
php artisan key:generate
```

Depois, edite o arquivo `.env` e configure os seguintes parâmetros conforme o seu ambiente:

```env
APP_NAME="Licencas"
APP_ENV=local
APP_KEY=            # Preenchido automaticamente pelo comando key:generate
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=licencas
DB_USERNAME=root
DB_PASSWORD=        # Sua senha do banco de dados
```

⚠️ **Importante:**  
- Nunca envie o arquivo `.env` para o repositório.  
- Apenas o `.env.example` deve ser versionado como modelo de configuração.  

---

## 👤 Usuário Administrador Padrão

- **Email:** admin@example.com  
- **Senha:** admin123  

---

## 📝 Script de Instalação (`install.sh`)

```bash
#!/bin/bash

echo "🚀 Instalando dependências..."
composer install

echo "📦 Instalando frontend..."
npm install && npm run dev

echo "🔑 Configurando ambiente..."
cp .env.example .env
php artisan key:generate

echo "🛠️ Executando instalação do sistema..."
php artisan install

echo "✅ Instalação completa! Acesse o sistema e faça login com: admin@example.com / admin123"
```

---

## ⚙️ Manual - Instalação Passo a Passo

### 1. Instale dependências PHP

```bash
composer install
```

### 2. Instale dependências JavaScript

```bash
npm install && npm run dev
```

### 3. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite `.env` e configure banco de dados e outros parâmetros.

### 4. Execute a instalação

```bash
php artisan install
```

---

## 📝 Licença

Este projeto está licenciado sob os termos da Licença MIT.  
Veja o arquivo [LICENSE](LICENSE) para mais informações.
