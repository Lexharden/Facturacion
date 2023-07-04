@extends('layouts.app')

@section('content')
    @include('facturacion::layouts.nav')

    <div class="container">
        <h1>Corporativos</h1>
        <table class="table">
            <thead>
            <tr>
                <th>Corporativo ID</th>
                <th>Corporativo</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($corporativos as $corporativo)
                <tr>
                    <td>{{ $corporativo->id }}</td>
                    <td>{{ $corporativo->name }}</td>
                    <td>
                        <a href="{{ route('configuracion.sucursal', ['id' => $corporativo->id]) }}" class="btn btn-primary">Configurar sucursal</a>
{{--                        <a href="{{ route('configuracion.sucursal', ['id' => $corporativo->id_location, 'prefix_location' => $corporativo->prefix_location, 'ubicacion' => $corporativo->location_name]) }}" class="btn btn-primary">Configurar sucursal</a>--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
