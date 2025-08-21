@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Nueva Compra</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('compras.store') }}" method="POST" id="compraForm">
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
                    <label for="proveedor_id">Proveedor:</label>
                    <select name="proveedor_id" class="form-control" required>
                        <option value="">Seleccionar proveedor</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" 
                                {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
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
                                    data-precio="{{ $prod->precio_compra }}"
                                    {{ $producto['id'] == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->nombre }} - ${{ $prod->precio_compra }}
                                </option>
                            @endforeach
                        </select>
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
                                <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_compra }}">
                                    {{ $producto->nombre }} - ${{ $producto->precio_compra }}
                                </option>
                            @endforeach
                        </select>
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

        <button type="submit" class="btn btn-primary mt-3">Registrar Compra</button>
        <a href="{{ route('compras.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let productoCount = {{ old('productos') ? count(old('productos')) : 1 }};
    
    document.getElementById('add-producto').addEventListener('click', function() {
        const container = document.getElementById('productos-container');
        const newRow = container.firstElementChild.cloneNode(true);
        
        newRow.querySelector('.producto-select').selectedIndex = 0;
        newRow.querySelector('.cantidad').value = '';
        newRow.querySelector('.precio').value = '';
        
        const inputs = newRow.querySelectorAll('[name]');
        inputs.forEach(input => {
            const name = input.name;
            const newName = name.replace(/\[\d+\]/g, `[${productoCount}]`);
            input.name = newName;
        });
        
        container.appendChild(newRow);
        productoCount++;
        
        newRow.querySelector('.producto-select').addEventListener('change', updatePrecio);
        newRow.querySelector('.cantidad').addEventListener('input', calcularTotal);
        newRow.querySelector('.precio').addEventListener('input', calcularTotal);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-producto')) {
            if (document.querySelectorAll('.producto-row').length > 1) {
                e.target.closest('.producto-row').remove();
                calcularTotal();
            }
        }
    });

    function updatePrecio(e) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const precioInput = e.target.closest('.producto-row').querySelector('.precio');
        if (selectedOption.dataset.precio) {
            precioInput.value = selectedOption.dataset.precio;
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

    document.querySelectorAll('.producto-select').forEach(select => {
        select.addEventListener('change', updatePrecio);
    });
    
    document.querySelectorAll('.cantidad, .precio').forEach(input => {
        input.addEventListener('input', calcularTotal);
    });

    calcularTotal();
});
</script>
@endsection