@extends('layouts.app')

@section('content')
    <div class="container">
        @include('facturacion::layouts.nav')
        <h1>Clientes</h1>
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
        <div class="row mb-3">
            <div class="col-md-10">
                <form action="{{ route('clientes.obtenerClientes') }}" method="GET" class="form-inline">
                    <!-- Código del formulario de búsqueda -->
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Nombre Fiscal</th>
                    <th>RFC</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $customer)
                    <tr>
                        <td>{{ $customer->legal_name }}</td>
                        <td>{{ $customer->tax_id }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td class="text-center">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#editModal{{ $customer->id }}">Editar</button>
                            </div>
                            <!-- Modal para la edición del cliente -->
                            <div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $customer->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $customer->id }}">Editar Cliente</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Formulario para editar el cliente -->
                                            <form action="{{ route('clientes.actualizarCliente', $customer->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="editLegalName{{ $customer->id }}">Nombre Fiscal</label>
                                                            <input type="text" class="form-control" id="editLegalName{{ $customer->id }}" name="legal_name" value="{{ $customer->legal_name }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editTaxId{{ $customer->id }}">RFC</label>
                                                            <input type="text" class="form-control" id="editTaxId{{ $customer->id }}" name="tax_id" value="{{ $customer->tax_id }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editTaxSystem{{ $customer->id }}">Regimen Fiscal</label>
                                                            <input type="text" class="form-control" id="editTaxSystem{{ $customer->id }}" name="tax_system" value="{{ $customer->tax_system }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editEmail{{ $customer->id }}">Email</label>
                                                            <input type="email" class="form-control" id="editEmail{{ $customer->id }}" name="email" value="{{ $customer->email }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editPhone{{ $customer->id }}">Teléfono</label>
                                                            <input type="text" class="form-control" id="editPhone{{ $customer->id }}" name="phone" value="{{ $customer->phone }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="editStreet{{ $customer->id }}">Calle</label>
                                                            <input type="text" class="form-control" id="editStreet{{ $customer->id }}" name="address[street]" value="{{ $customer->address->street }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editExterior{{ $customer->id }}">Número Exterior</label>
                                                            <input type="text" class="form-control" id="editExterior{{ $customer->id }}" name="address[exterior]" value="{{ $customer->address->exterior }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editInterior{{ $customer->id }}">Número Interior</label>
                                                            <input type="text" class="form-control" id="editInterior{{ $customer->id }}" name="address[interior]" value="{{ $customer->address->interior }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editNeighborhood{{ $customer->id }}">Colonia</label>
                                                            <input type="text" class="form-control" id="editNeighborhood{{ $customer->id }}" name="address[neighborhood]" value="{{ $customer->address->neighborhood }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editCity{{ $customer->id }}">Ciudad</label>
                                                            <input type="text" class="form-control" id="editCity{{ $customer->id }}" name="address[city]" value="{{ $customer->address->city }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editMunicipality{{ $customer->id }}">Municipio</label>
                                                            <input type="text" class="form-control" id="editMunicipality{{ $customer->id }}" name="address[municipality]" value="{{ $customer->address->municipality }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editZip{{ $customer->id }}">Código Postal</label>
                                                            <input type="text" class="form-control" id="editZip{{ $customer->id }}" name="address[zip]" value="{{ $customer->address->zip }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editState{{ $customer->id }}">Estado</label>
                                                            <input type="text" class="form-control" id="editState{{ $customer->id }}" name="address[state]" value="{{ $customer->address->state }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Agrega campos adicionales según tus necesidades -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
