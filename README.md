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

## 🚀 Instalação via Script

### 1. Clone o repositório

Copie e cole os comandos abaixo dentro do seu terminal Linux.

```bash
cd /var/www/
git clone https://github.com/dutr4/licencas.git
cd licencas
```
### 2. Torne o script de instalação executável

```bash
chmod +x install.sh
```

### 3. 📝 Execute a instalação automática (`install.sh`)

Copie e cole:

```bash
./install.sh
```
---

Isso irá:  
✅ Detectar o sistema operacional
✅ Instalar dependências PHP, NodeJS, MySQL e Apache automaticamente
✅ Configurar .env e gerar chave
✅ Rodar migrations e seeders
✅ Criar link simbólico storage
✅ Limpar cache de configuração 

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

### 4. Execute a instalação

```bash
php artisan install
```

---

## 👤 Usuário Administrador Padrão

- **Email:** admin@email.com  
- **Senha:** admin54321

---

## 📝 Licença

Este projeto está licenciado sob os termos da Licença MIT.  
Veja o arquivo [LICENSE](LICENSE) para mais informações.
