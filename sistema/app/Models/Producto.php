<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 *
 * @property $id
 * @property $categoria_id
 * @property $proveedor_id
 * @property $nombre
 * @property $precio_compra
 * @property $precio_venta
 * @property $cantidad
 * @property $created_at
 * @property $updated_at
 *
 * @property Categoria $categoria
 * @property Proveedore $proveedore
 * @property DetalleCompra[] $detalleCompras
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Producto extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['categoria_id', 'proveedor_id', 'nombre', 'precio_compra', 'precio_venta', 'cantidad'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        return $this->belongsTo(\App\Models\Categoria::class, 'categoria_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedore()
    {
        return $this->belongsTo(\App\Models\Proveedore::class, 'proveedor_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleCompras()
    {
        return $this->hasMany(\App\Models\DetalleCompra::class, 'id', 'producto_id');
    }
    
}
