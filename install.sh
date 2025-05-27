#!/bin/bash

echo "🚀 Detectando sistema operacional..."

if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$ID
else
    echo "❌ Sistema operacional não suportado."
    exit 1
fi

echo "✅ Sistema detectado: $OS"

# Função de instalação para Debian/Ubuntu
install_apt() {
    echo "🔧 Atualizando pacotes..."
    sudo apt update && sudo apt upgrade -y

    echo "🔧 Instalando Apache..."
    sudo apt install -y apache2

    echo "🔧 Instalando PHP e extensões..."
    sudo apt install -y php php-mbstring php-xml php-bcmath php-curl php-mysql unzip

    echo "🔧 Instalando Composer..."
    sudo apt install -y composer

    echo "🔧 Instalando Node.js e npm..."
    sudo apt install -y nodejs npm

    echo "🔧 Instalando MySQL Server..."
    sudo apt install -y mysql-server
}

# Função de instalação para CentOS/RHEL
install_yum() {
    echo "🔧 Atualizando pacotes..."
    sudo yum update -y

    echo "🔧 Instalando Apache..."
    sudo yum install -y httpd

    echo "🔧 Instalando PHP e extensões..."
    sudo yum install -y php php-mbstring php-xml php-bcmath php-curl php-mysqlnd unzip

    echo "🔧 Instalando Composer..."
    sudo yum install -y composer

    echo "🔧 Instalando Node.js e npm..."
    sudo yum install -y nodejs npm

    echo "🔧 Instalando MySQL Server..."
    sudo yum install -y mysql-server
}

if [[ "$OS" == "ubuntu" || "$OS" == "debian" ]]; then
    install_apt
elif [[ "$OS" == "centos" || "$OS" == "rhel" ]]; then
    install_yum
else
    echo "❌ Sistema operacional $OS não suportado automaticamente."
    exit 1
fi

echo "✅ Dependências instaladas com sucesso."

echo "🚀 Instalando dependências do projeto..."

composer install

npm install && npm run dev

echo "🔑 Configurando ambiente..."

if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "✅ Arquivo .env criado a partir do .env.example."
fi

php artisan key:generate

echo "🛠️ Executando instalação do sistema..."

php artisan install

echo "✅ Instalação completa! Acesse o sistema e faça login com: admin@example.com / admin123"
