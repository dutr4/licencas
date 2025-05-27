<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======

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
>>>>>>> 4d5983954891169ea416e8cda92619193d171a4f
