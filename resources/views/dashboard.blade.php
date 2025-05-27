@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
	<h1>Dashboard</h1>
<div>
    <label>Divisão:</label>
    <select id="filtroDivisao">
        <option value="">Todas</option>
        @foreach($divisoes as $divisao)
            <option value="{{ $divisao }}">{{ $divisao }}</option>
        @endforeach
    </select>

    <label>Empresa:</label>
    <select id="filtroEmpresa">
        <option value="">Todas</option>
        @foreach($empresas as $empresa)
            <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
        @endforeach
    </select>

    <label>Filial:</label>
    <select id="filtroFilial">
        <option value="">Todas</option>
        @foreach($filiais as $filial)
            <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
        @endforeach
    </select>

    <label>Setor:</label>
    <select id="filtroSetor">
        <option value="">Todos</option>
        @foreach($setores as $setor)
            <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
        @endforeach
    </select>

<button class="btn btn-primary btn-sm mt-2" id="aplicarFiltros">Aplicar Filtros</button>

<button class="btn btn-secondary btn-sm mt-2" id="limparFiltros">Limpar Filtros</button>

</div>
<div class="alert alert-info mt-3">
    <strong>Filtros selecionados:</strong><br>
    Divisão: <span id="filtroSelecionadoDivisao"></span> | 
    Empresa: <span id="filtroSelecionadoEmpresa"></span> | 
    Filial: <span id="filtroSelecionadoFilial"></span> | 
    Setor: <span id="filtroSelecionadoSetor"></span>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Licenças: Livre vs Em Uso</div>
            <div class="card-body">
                <canvas id="graficoStatus" width="300" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Licenças em Uso por Versão</div>
            <div class="card-body">
                <canvas id="graficoEmUsoPorVersao" width="300" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Licenças Livres por Versão</div>
            <div class="card-body">
                <canvas id="graficoLivresPorVersao" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById('filtroDivisao').addEventListener('change', function() {
    const divisao = this.value;
    fetch('/filtros/empresas?divisao=' + divisao)
        .then(response => response.json())
        .then(data => {
            const empresaSelect = document.getElementById('filtroEmpresa');
            empresaSelect.innerHTML = '<option value="">Todas</option>';
            data.forEach(empresa => {
                empresaSelect.innerHTML += `<option value="${empresa.id}">${empresa.nome}</option>`;
            });
            // Reset filiais e setores
            document.getElementById('filtroFilial').innerHTML = '<option value="">Todas</option>';
            document.getElementById('filtroSetor').innerHTML = '<option value="">Todos</option>';
        });
});

document.getElementById('filtroEmpresa').addEventListener('change', function() {
    const empresaId = this.value;
    fetch('/filtros/filiais?empresa_id=' + empresaId)
        .then(response => response.json())
        .then(data => {
            const filialSelect = document.getElementById('filtroFilial');
            filialSelect.innerHTML = '<option value="">Todas</option>';
            data.forEach(filial => {
                filialSelect.innerHTML += `<option value="${filial.id}">${filial.nome}</option>`;
            });
            document.getElementById('filtroSetor').innerHTML = '<option value="">Todos</option>';
        });
});

document.getElementById('filtroFilial').addEventListener('change', function() {
    const filialId = this.value;
    fetch('/filtros/setores?filial_id=' + filialId)
        .then(response => response.json())
        .then(data => {
            const setorSelect = document.getElementById('filtroSetor');
            setorSelect.innerHTML = '<option value="">Todos</option>';
            data.forEach(setor => {
                setorSelect.innerHTML += `<option value="${setor.id}">${setor.nome}</option>`;
            });
        });
});

document.getElementById('limparFiltros').addEventListener('click', function() {
    document.getElementById('filtroDivisao').value = "";
    document.getElementById('filtroEmpresa').value = "";
    document.getElementById('filtroFilial').value = "";
    document.getElementById('filtroSetor').value = "";
    carregarGraficos();
});

document.getElementById('aplicarFiltros').addEventListener('click', function() {
    carregarGraficos();
});


let graficoStatus = null;
let graficoEmUsoPorVersao = null;
let graficoLivresPorVersao = null;

function carregarGraficos() {
    const divisaoSelect = document.getElementById('filtroDivisao');
    let divisao = divisaoSelect.value;
    const empresa = document.getElementById('filtroEmpresa').value;
    const filial = document.getElementById('filtroFilial').value;
    const setor = document.getElementById('filtroSetor').value;

    const params = new URLSearchParams({ divisao, empresa, filial, setor });

    document.getElementById('filtroSelecionadoDivisao').innerText = document.getElementById('filtroDivisao').value || 'Todas';
    document.getElementById('filtroSelecionadoEmpresa').innerText = document.getElementById('filtroEmpresa').selectedOptions[0].text;
    document.getElementById('filtroSelecionadoFilial').innerText = document.getElementById('filtroFilial').selectedOptions[0].text;
    document.getElementById('filtroSelecionadoSetor').innerText = document.getElementById('filtroSetor').selectedOptions[0].text;


    fetch('/dashboard/licencas-status?' + params)
        .then(r => r.json())
        .then(data => {
            const ctx = document.getElementById('graficoStatus').getContext('2d');
            if (graficoStatus) { 
	    graficoStatus.destroy(); }
	    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            graficoStatus = new Chart(ctx, {
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

    fetch('/dashboard/licencas-em-uso-por-versao?' + params)
        .then(r => r.json())
        .then(data => {
            const ctx = document.getElementById('graficoEmUsoPorVersao').getContext('2d');
            if (graficoEmUsoPorVersao) { graficoEmUsoPorVersao.destroy(); }
	    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
	    graficoEmUsoPorVersao = new Chart(ctx, {
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

    fetch('/dashboard/licencas-livres-por-versao?' + params)
        .then(r => r.json())
        .then(data => {
            const ctx = document.getElementById('graficoLivresPorVersao').getContext('2d');
            if (graficoLivresPorVersao) { graficoLivresPorVersao.destroy(); }
	    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
	    graficoLivresPorVersao = new Chart(ctx, {
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


window.onload = carregarGraficos;
</script>
@endsection
@endsection


