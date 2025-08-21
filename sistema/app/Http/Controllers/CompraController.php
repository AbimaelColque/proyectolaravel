<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedore;
use App\Models\Producto;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with('proveedor', 'detalles.producto')->latest()->get();
        return view('compra.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedore::all();
        $productos = Producto::with('categoria')->get();
        return view('compra.create', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        Log::info('Datos recibidos:', $request->all());
        
        $request->validate([
            'fecha' => 'required|date',
            'proveedor_id' => 'required|exists:proveedores,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            $productosData = [];

            Log::info('Productos recibidos:', $request->productos);

            foreach ($request->productos as $index => $producto) {
                $productoModel = Producto::find($producto['id']);
                if (!$productoModel) {
                    throw new \Exception("Producto ID {$producto['id']} no encontrado");
                }

                $cantidad = (int) $producto['cantidad'];
                $precio = (float) $producto['precio'];
                $subtotal = $cantidad * $precio;
                $total += $subtotal;

                $productosData[] = [
                    'producto_id' => $producto['id'],
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'total' => $subtotal
                ];

                Log::info("Producto {$index}:", [
                    'id' => $producto['id'],
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $subtotal
                ]);
            }

            Log::info("Total calculado: {$total}");

            $compra = Compra::create([
                'fecha' => $request->fecha,
                'proveedor_id' => $request->proveedor_id,
                'total' => $total
            ]);

            Log::info("Compra creada ID: {$compra->id}");

            foreach ($productosData as $detalle) {
                $detalleCompra = $compra->detalles()->create($detalle);
                Log::info("Detalle creado ID: {$detalleCompra->id}");

                $producto = Producto::find($detalle['producto_id']);
                if ($producto) {
                    $nuevaCantidad = $producto->cantidad + $detalle['cantidad'];
                    $producto->update(['cantidad' => $nuevaCantidad]);
                    Log::info("Stock actualizado producto {$producto->id}: {$nuevaCantidad}");
                }
            }

            DB::commit();

            return redirect()->route('compra.index')
                ->with('success', 'Compra registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear compra: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error al registrar la compra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Compra $compra)
    {
        $compra->load('proveedore', 'detalles.producto');
        return view('compra.show', compact('compra'));
    }

    public function edit(Compra $compra)
    {
        
        $proveedores = Proveedore::all();
        $productos = Producto::all();
        $compra->load('detalles');
        
        return view('compra.edit', compact('compra', 'proveedores', 'productos'));
    }

    public function destroy(Compra $compra)
    {
        try {
            DB::beginTransaction();

            
            foreach ($compra->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                $producto->decrement('cantidad', $detalle->cantidad);
            }

            
            $compra->detalles()->delete();
            $compra->delete();

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', 'Compra eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
}