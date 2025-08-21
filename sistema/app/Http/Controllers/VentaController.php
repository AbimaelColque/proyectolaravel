<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('cliente', 'detalles.producto')->latest()->get();
        return view('venta.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::with('categoria')->where('cantidad', '>', 0)->get();
        return view('venta.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        Log::info('Datos recibidos para venta:', $request->all());
        
        $request->validate([
            'fecha' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
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

            // Validar stock disponible
            foreach ($request->productos as $index => $producto) {
                $productoModel = Producto::find($producto['id']);
                if (!$productoModel) {
                    throw new \Exception("Producto ID {$producto['id']} no encontrado");
                }

                if ($productoModel->cantidad < $producto['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$productoModel->nombre}. Stock actual: {$productoModel->cantidad}");
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

            // Crear la venta
            $venta = Venta::create([
                'fecha' => $request->fecha,
                'cliente_id' => $request->cliente_id,
                'total' => $total
            ]);

            Log::info("Venta creada ID: {$venta->id}");

            // Crear detalles y actualizar stock
            foreach ($productosData as $detalle) {
                $detalleVenta = DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio' => $detalle['precio'],
                    'total' => $detalle['total']
                ]);
                
                Log::info("Detalle venta creado ID: {$detalleVenta->id}");

                // Actualizar stock del producto (reducir)
                $producto = Producto::find($detalle['producto_id']);
                if ($producto) {
                    $nuevaCantidad = $producto->cantidad - $detalle['cantidad'];
                    $producto->update(['cantidad' => $nuevaCantidad]);
                    Log::info("Stock actualizado producto {$producto->id}: {$nuevaCantidad}");
                }
            }

            DB::commit();

            return redirect()->route('venta.index') // Cambié 'ventas.index' a 'venta.index'
                ->with('success', 'Venta registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear venta: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Venta $venta)
    {
        $venta->load('cliente', 'detalles.producto');
        
        return view('venta.show', compact('venta'));
    }

    public function destroy(Venta $venta)
    {
        try {
            DB::beginTransaction();

            // Revertir stock de productos (aumentar)
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->increment('cantidad', $detalle->cantidad);
                }
            }

            // Eliminar detalles y luego la venta
            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();

            return redirect()->route('venta.index') // Cambié 'ventas.index' a 'venta.index'
                ->with('success', 'Venta eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }
}