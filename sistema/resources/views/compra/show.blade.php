@extends('layouts.app')

@section('template_title')
    {{ $compra->name ?? __('Show') . " " . __('Compra') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Compra</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('compras.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha:</strong>
                                    {{ $compra->fecha }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Total:</strong>
                                    {{ $compra->total }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Proveedor Id:</strong>
                                    {{ $compra->proveedor_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
