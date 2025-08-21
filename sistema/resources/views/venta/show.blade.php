@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle de Compra #{{ $venta->id }}</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h4>Información de la Compra</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> {{ $venta->fecha->format('d/m/Y') }}</p>
                    <p><strong>Cliente:</strong> {{ $venta->cliente ? $venta->cliente->nombre : 'Sin cliente' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total:</strong> ${{ number_format($venta->total, 2) }}</p>
                    <p><strong>Registrado:</strong> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Productos Vendidos</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto ? $detalle->producto->nombre : 'Producto eliminado' }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precio, 2) }}</td>
                        <td>${{ number_format($detalle->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total:</th>
                        <th>${{ number_format($venta->total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <a href="{{ route('ventas.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection