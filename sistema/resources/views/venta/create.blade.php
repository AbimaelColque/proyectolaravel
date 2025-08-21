@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Nueva Venta</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm">
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" name="fecha" class="form-control" 
                           value="{{ old('fecha', now()->format('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cliente_id">Cliente:</label>
                    <select name="cliente_id" class="form-control" required>
                        <option value="">Seleccionar cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" 
                                {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <h3>Productos</h3>
        <div id="productos-container">
            @if(old('productos'))
                @foreach(old('productos') as $index => $producto)
                <div class="producto-row row mb-2">
                    <div class="col-md-5">
                        <select name="productos[{{ $index }}][id]" class="form-control producto-select" required>
                            <option value="">Seleccionar producto</option>
                            @foreach($productos as $prod)
                                <option value="{{ $prod->id }}" 
                                    data-precio="{{ $prod->precio_venta }}"
                                    data-stock="{{ $prod->cantidad }}"
                                    {{ $producto['id'] == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->nombre }} - Stock: {{ $prod->cantidad }} - ${{ $prod->precio_venta }}
                                </option>
                            @endforeach
                        </select>
                        <small class="stock-info text-muted"></small>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="productos[{{ $index }}][cantidad]" 
                               class="form-control cantidad" min="1" 
                               value="{{ $producto['cantidad'] }}" placeholder="Cantidad" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="productos[{{ $index }}][precio]" 
                               class="form-control precio" step="0.01" min="0" 
                               value="{{ $producto['precio'] }}" placeholder="Precio" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-producto">Eliminar</button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="producto-row row mb-2">
                    <div class="col-md-5">
                        <select name="productos[0][id]" class="form-control producto-select" required>
                            <option value="">Seleccionar producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" 
                                    data-precio="{{ $producto->precio_venta }}"
                                    data-stock="{{ $producto->cantidad }}">
                                    {{ $producto->nombre }} - Stock: {{ $producto->cantidad }} - ${{ $producto->precio_venta }}
                                </option>
                            @endforeach
                        </select>
                        <small class="stock-info text-muted"></small>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="productos[0][cantidad]" class="form-control cantidad" min="1" placeholder="Cantidad" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="productos[0][precio]" class="form-control precio" step="0.01" min="0" placeholder="Precio" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-producto">Eliminar</button>
                    </div>
                </div>
            @endif
        </div>

        <button type="button" id="add-producto" class="btn btn-secondary mt-2">Agregar Producto</button>

        <div class="form-group mt-3">
            <label for="total">Total: $</label>
            <span id="total">0.00</span>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Registrar Venta</button>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let productoCount = {{ old('productos') ? count(old('productos')) : 1 }};
    
    // Agregar producto
    document.getElementById('add-producto').addEventListener('click', function() {
        const container = document.getElementById('productos-container');
        const newRow = container.firstElementChild.cloneNode(true);
        
        // Limpiar valores
        newRow.querySelector('.producto-select').selectedIndex = 0;
        newRow.querySelector('.cantidad').value = '';
        newRow.querySelector('.precio').value = '';
        newRow.querySelector('.stock-info').textContent = '';
        
        // Actualizar names
        const inputs = newRow.querySelectorAll('[name]');
        inputs.forEach(input => {
            const name = input.name;
            const newName = name.replace(/\[\d+\]/g, `[${productoCount}]`);
            input.name = newName;
        });
        
        container.appendChild(newRow);
        productoCount++;
        
        // Agregar eventos
        newRow.querySelector('.producto-select').addEventListener('change', updateProductoInfo);
        newRow.querySelector('.cantidad').addEventListener('input', calcularTotal);
        newRow.querySelector('.precio').addEventListener('input', calcularTotal);
    });

    // Eliminar producto
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-producto')) {
            if (document.querySelectorAll('.producto-row').length > 1) {
                e.target.closest('.producto-row').remove();
                calcularTotal();
            }
        }
    });

    function updateProductoInfo(e) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const precioInput = e.target.closest('.producto-row').querySelector('.precio');
        const stockInfo = e.target.closest('.producto-row').querySelector('.stock-info');
        
        if (selectedOption.dataset.precio) {
            precioInput.value = selectedOption.dataset.precio;
        }
        
        if (selectedOption.dataset.stock) {
            stockInfo.textContent = `Stock disponible: ${selectedOption.dataset.stock}`;
        }
        
        calcularTotal();
    }

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.producto-row').forEach(row => {
            const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(row.querySelector('.precio').value) || 0;
            total += cantidad * precio;
        });
        document.getElementById('total').textContent = total.toFixed(2);
    }

    // Event listeners iniciales
    document.querySelectorAll('.producto-select').forEach(select => {
        select.addEventListener('change', updateProductoInfo);
        // Actualizar info del producto seleccionado inicialmente
        if (select.value) {
            updateProductoInfo({target: select});
        }
    });
    
    document.querySelectorAll('.cantidad, .precio').forEach(input => {
        input.addEventListener('input', calcularTotal);
    });

    // Calcular total inicial
    calcularTotal();
});
</script>
@endsection