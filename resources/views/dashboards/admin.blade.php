<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    label {
        margin-right: 5px;
    }
    select {
        margin-right: 15px;
        padding: 5px;
    }
    canvas {
        max-width: 400px;
        margin-top: 20px;
        display: block;
    }
</style>

</head>
<body>
    <h1>Bem-vindo, Administrador!</h1>

<div>
    <label>Divisão:</label>
    <select id="filtroDivisao">
        <option value="">Todas</option>
        @foreach(\App\Models\Empresa::select('divisao')->distinct()->get() as $divisao)
            <option value="{{ $divisao->divisao }}">{{ $divisao->divisao }}</option>
        @endforeach
    </select>

    <label>Empresa:</label>
    <select id="filtroEmpresa">
        <option value="">Todas</option>
        @foreach(\App\Models\Empresa::all() as $empresa)
            <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
        @endforeach
    </select>

    <label>Filial:</label>
    <select id="filtroFilial">
        <option value="">Todas</option>
        @foreach(\App\Models\Filial::all() as $filial)
            <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
        @endforeach
    </select>

    <label>Setor:</label>
    <select id="filtroSetor">
        <option value="">Todos</option>
        @foreach(\App\Models\Setor::all() as $setor)
            <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
        @endforeach
    </select>
</div>

    <canvas id="graficoStatus" width="400" height="400"></canvas>
    <canvas id="graficoEmUsoPorVersao" width="400" height="400"></canvas>
    <canvas id="graficoLivresPorVersao" width="400" height="400"></canvas>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Sair</button>
    </form>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function carregarGraficos() {
    const divisao = document.getElementById('filtroDivisao').value;
    const empresa = document.getElementById('filtroEmpresa').value;
    const filial = document.getElementById('filtroFilial').value;
    const setor = document.getElementById('filtroSetor').value;

    const params = new URLSearchParams({ divisao, empresa, filial, setor });

    // Gráfico 1
    fetch('/admin/dashboard/licencas-status?' + params)
        .then(r => r.json())
        .then(data => {
            const ctx = document.getElementById('graficoStatus').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.map(d => d.status),
                    datasets: [{
                        data: data.map(d => d.total),
                        backgroundColor: ['#3b82f6', '#10b981'],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                }
            });
        });

    // Gráfico 2
    fetch('/admin/dashboard/licencas-em-uso-por-versao?' + params)
        .then(r => r.json())
        .then(data => {
            const ctx = document.getElementById('graficoEmUsoPorVersao').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.versao),
                    datasets: [{
                        label: 'Em uso',
                        data: data.map(d => d.total),
                        backgroundColor: '#3b82f6'
                    }]
                }
            });
        });

    // Gráfico 3
    fetch('/admin/dashboard/licencas-livres-por-versao?' + params)
        .then(r => r.json())
        .then(data => {
            const ctx = document.getElementById('graficoLivresPorVersao').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.versao),
                    datasets: [{
                        label: 'Disponíveis',
                        data: data.map(d => d.total),
                        backgroundColor: '#10b981'
                    }]
                }
            });
        });
}

// Recarregar ao mudar qualquer filtro
['filtroDivisao', 'filtroEmpresa', 'filtroFilial', 'filtroSetor'].forEach(id => {
    document.getElementById(id).addEventListener('change', carregarGraficos);
});

window.onload = carregarGraficos;
</script>
</body>
</html>
