@extends('layouts.app')

@section('content')
    <div class="container">
        @include('facturacion::layouts.nav')
        <h1>Subir Certificados CSD</h1>

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

        <form method="POST" action="{{ route('csd.upload') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="organization_id">ID de la organización <span class="text-danger">*</span>
                    <small>(Solicitarlo al administrador)</small>
                </label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="organization_id" name="organization_id" required>
                </div>
            </div>

            <div class="form-group">
                <label for="cerFile">Archivo .cer <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <input type="file" class="form-control" id="cerFile" name="cerFile" required accept=".cer">
                </div>
            </div>

            <div class="form-group">
                <label for="keyFile">Archivo .key <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <input type="file" class="form-control" id="keyFile" name="keyFile" required accept=".key">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Subir Certificados</button>
                </div>
            </div>
        </form>
    </div>
    <style>
        .form-group label small {
            color: red;
        }
    </style>
@endsection
