@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Ventas</h1>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary mb-3">Nueva Venta</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->fecha->format('d/m/Y') }}</td>
                        <td>{{ $venta->cliente ? $venta->cliente->nombre : 'Sin cliente' }}</td>
                        <td>${{ number_format($venta->total, 2) }}</td>
                        <td>
                            <a href="{{ route('ventas.show', $venta) }}" class="btn btn-info btn-sm">Ver</a>
                            <form action="{{ route('ventas.destroy', $venta) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar venta?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection