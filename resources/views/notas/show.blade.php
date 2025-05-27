@extends('adminlte::page')

@section('title', 'Detalhes da Nota Fiscal')

@section('content_header')
    <h1>Nota Fiscal {{ $nota->numero }}</h1>
@endsection

@section('content')
    <a href="{{ route('notas.index') }}" class="btn btn-secondary mb-3">Voltar</a>

    <div class="mb-4">
        <p><strong>Empresa:</strong> {{ $nota->empresa->nome }}</p>
        <p><strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($nota->data_emissao)->format('d/m/Y') }}</p>
        <p><strong>Arquivo:</strong>
            @if ($nota->arquivo)
                <a href="{{ asset('storage/' . $nota->arquivo) }}" target="_blank">Visualizar PDF</a>
            @else
                Nenhum arquivo enviado
            @endif
        </p>
    </div>

    <hr>
    <h4>Itens da Nota</h4>
    <a href="{{ route('notas.itens.create', $nota) }}" class="btn btn-success mb-2">Adicionar Item</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($nota->itens->isEmpty())
        <p>Nenhum item cadastrado.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($nota->itens as $item)
                    <tr>
                        <td>{{ $item->descricao }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>
                            <form action="{{ route('notas.itens.destroy', $item) }}" method="POST" onsubmit="return confirm('Deseja remover este item?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
