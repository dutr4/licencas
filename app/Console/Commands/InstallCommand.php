<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    protected $description = 'Instala e prepara o sistema (migrate, seed, storage link, config clear)';

    public function handle()
{
    $this->info('🛠️ Iniciando instalação do sistema...');

    $this->call('migrate:fresh', ['--seed' => true]);
    $this->info('✅ Migrations e Seeders executados.');

    $this->call('storage:link');
    $this->info('✅ Link simbólico criado.');

    $this->call('config:clear');
    $this->info('✅ Cache de configuração limpo.');

    $this->info('🚀 Instalação concluída com sucesso!');
}
}
