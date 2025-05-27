<form method="GET" action="{{ $action ?? url()->current() }}" class="mb-3">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar..." value="{{ request('busca') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </div>
</form>
