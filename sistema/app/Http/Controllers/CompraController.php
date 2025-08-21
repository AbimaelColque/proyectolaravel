<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CompraRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $compras = Compra::paginate();

        return view('compra.index', compact('compras'))
            ->with('i', ($request->input('page', 1) - 1) * $compras->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $compra = new Compra();

        return view('compra.create', compact('compra'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompraRequest $request): RedirectResponse
    {
        Compra::create($request->validated());

        return Redirect::route('compras.index')
            ->with('success', 'Compra created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $compra = Compra::find($id);

        return view('compra.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $compra = Compra::find($id);

        return view('compra.edit', compact('compra'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompraRequest $request, Compra $compra): RedirectResponse
    {
        $compra->update($request->validated());

        return Redirect::route('compras.index')
            ->with('success', 'Compra updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Compra::find($id)->delete();

        return Redirect::route('compras.index')
            ->with('success', 'Compra deleted successfully');
    }
}
