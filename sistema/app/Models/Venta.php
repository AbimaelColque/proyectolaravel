<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 *
 * @property $id
 * @property $fecha
 * @property $total
 * @property $created_at
 * @property $updated_at
 * @property $cliente_id
 *
 * @property Cliente $cliente
 * @property DetalleVenta[] $detalleVentas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Venta extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['fecha', 'total', 'cliente_id'];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalles()
    {
        // return $this->hasMany(\App\Models\DetalleVenta::class, 'id', 'venta_id');
        return $this->hasMany(DetalleVenta::class);
    }
    
}
