<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        

        <div class="form-group mb-2 mb20">
        <label for="categoria_id" class="form-label">{{ __('Categoría') }}</label>
<select name="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror" id="categoria_id">
    <option value="">Seleccione una categoría</option>
    @foreach($categorias as $categoria)
        <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto?->categoria_id) == $categoria->id ? 'selected' : '' }}>
            {{ $categoria->nombre }}
        </option>
    @endforeach
</select>
{!! $errors->first('categoria_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}    
        </div>

        

        <div class="form-group mb-2 mb20">
        <label for="proveedor_id" class="form-label">{{ __('Proveedor') }}</label>
<select name="proveedor_id" class="form-control @error('proveedor_id') is-invalid @enderror" id="proveedor_id">
    <option value="">Seleccione un Proveedor</option>
    @foreach($proveedores as $proveedor)
        <option value="{{ $proveedor->id }}" {{ old('proveedor_id', $producto?->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
            {{ $proveedor->nombre }}
        </option>
    @endforeach
</select>
{!! $errors->first('categoria_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}    
        </div>

        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $producto?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="precio_compra" class="form-label">{{ __('Precio Compra') }}</label>
            <input type="text" name="precio_compra" class="form-control @error('precio_compra') is-invalid @enderror" value="{{ old('precio_compra', $producto?->precio_compra) }}" id="precio_compra" placeholder="Precio Compra">
            {!! $errors->first('precio_compra', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="precio_venta" class="form-label">{{ __('Precio Venta') }}</label>
            <input type="text" name="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror" value="{{ old('precio_venta', $producto?->precio_venta) }}" id="precio_venta" placeholder="Precio Venta">
            {!! $errors->first('precio_venta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cantidad" class="form-label">{{ __('Cantidad') }}</label>
            <input type="text" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror" value="{{ old('cantidad', $producto?->cantidad) }}" id="cantidad" placeholder="Cantidad">
            {!! $errors->first('cantidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>