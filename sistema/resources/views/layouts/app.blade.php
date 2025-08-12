<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css" rel="stylesheet" integrity="sha384-e4/pU/7fdyaPKtXkqAgHNgoYAb2LNmChhpSuSp8o6saYtS2sP+JZsu8Wy/7mGV7w" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/buttons/3.2.4/css/buttons.bootstrap5.min.css" rel="stylesheet" integrity="sha384-DJhypeLg79qWALC844KORuTtaJcH45J+36wNgzj4d1Kv1vt2PtRuV2eVmdkVmf/U" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        th, td{
            text-align:center !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @if(Auth::check())
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('productos.index') }}">{{ __('Productos') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('categorias.index') }}">{{ __('Categorias') }}</a>
                        </li>

                    </ul>
                @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha384-+mbV2IY1Zk/X1p/nWllGySJSUN8uMs+gUAN10Or95UBH0fpj6GfKgPmgC5EXieXG" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js" integrity="sha384-RZEqG156bBQSxYY9lwjUz/nKVkqYj/QNK9dEjjyJ/EVTO7ndWwk6ZWEkvaKdRm/U" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js" integrity="sha384-G85lmdZCo2WkHaZ8U1ZceHekzKcg37sFrs4St2+u/r2UtfvSDQmQrkMsEx4Cgv/W" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/dataTables.buttons.min.js" integrity="sha384-W+u8oyKVP+xXIEiQx3Zffi+rKULG3CX+yYaA+Ww6nUjUsL8Pn6fBTE7swLLBtJWQ" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.bootstrap5.min.js" integrity="sha384-BdedgzbgcQH1hGtNWLD56fSa7LYUCzyRMuDzgr5+9etd1/W7eT0kHDrsADMmx60k" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.html5.min.js" integrity="sha384-+E6fb8f66UPOVDHKlEc1cfguF7DOTQQ70LNUnlbtywZiyoyQWqtrMjfTnWyBlN/Y" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.print.min.js" integrity="sha384-SbNRDguI7TGChBvvLf/a66MngtndT7TOurCIAwfkiKkzbM/hAsUwaL4MBBOgYWnH" crossorigin="anonymous"></script>


    <script>

        $(document).ready(function() {    
        $('#example').DataTable({        
            language: {
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast":"Último",
                        "sNext":"Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "sProcessing":"Procesando...",
                },
            //para usar los botones   
                responsive: "true",
                dom: 'Bfrtilp',       
                buttons:[ 
                    {
                        extend:    'excelHtml5',
                        text:      '<i class="fas fa-file-excel"></i> ',
                        titleAttr: 'Exportar a Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend:    'pdfHtml5',
                        text:      '<i class="fas fa-file-pdf"></i> ',
                        titleAttr: 'Exportar a PDF',
                        className: 'btn btn-danger'
                    },
                    {
                        extend:    'print',
                        text:      '<i class="fa fa-print"></i> ',
                        titleAttr: 'Imprimir',
                        className: 'btn btn-info'
                    },
                ]	        
            });     
        });

    </script>
</body>
</html>
