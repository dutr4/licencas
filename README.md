
# Sistema de Licenciamento

Sistema para gerenciar licenças de software, notas fiscais e inventário, desenvolvido em Laravel.

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
git clone https://github.com/dutr4/licencas.git
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

## 👤 Usuário Administrador Padrão

- **Email:** admin@email.com  
- **Senha:** admin54321  

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

echo "✅ Instalação completa! Acesse o sistema e faça login com: admin@email.com / admin54321"
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

MIT
