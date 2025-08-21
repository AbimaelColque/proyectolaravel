@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Compras</h1>
    <a href="{{ route('compras.create') }}" class="btn btn-primary mb-3">Nueva Compra</a>

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
                        <th>Proveedor</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compras as $compra)
                    <tr>
                        <td>{{ $compra->id }}</td>
                        <td>{{ $compra->fecha->format('d/m/Y') }}</td>
                        <td>{{ $compra->proveedor ? $compra->proveedor->nombre : 'Sin proveedor' }}</td>
                        <td>${{ number_format($compra->total, 2) }}</td>
                        <td>
                            <a href="{{ route('compras.show', $compra) }}" class="btn btn-info btn-sm">Ver</a>
                            <form action="{{ route('compras.destroy', $compra) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar compra?')">Eliminar</button>
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