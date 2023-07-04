@extends('layouts.app')

@section('content')
    @include('facturacion::layouts.nav')

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h1>Sucursales</h1>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Business ID</th>
                <th>Location ID</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($datos as $dato)
                <tr>
                    <td>{{ $dato->id }}</td>
                    <td>{{ $dato->name }}</td>
                    <td>{{ $dato->business_id }}</td>
                    <td>{{ $dato->location_id }}</td>
                    <td>
                        <form method="POST" action="{{ route('configuracion.crearOrganizacion') }}">
                            @csrf
                            <input type="hidden" name="name" value="{{ $dato->name }}">
                            <input type="hidden" name="id" value="{{ $dato->id }}">
                            <input type="hidden" name="location_id" value="{{ $dato->location_id }}">
                            <button type="submit" class="btn btn-primary">Crear organización</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
