@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        @include('facturacion::layouts.nav')
                        <h1>Ventas</h1>
                    </div>

                    <div class="card-body">
                        <form id="searchForm" class="mb-3">
                            <div class="input-group">
                                <input type="text" id="searchInvoice" class="form-control" name="search_invoice"
                                       placeholder="Buscar por número de factura"
                                       value="{{ request()->query('search_invoice') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <div style="overflow-x: auto;">
                            <table id="ventasTable" class="table">
                                <thead>
                                <tr>
                                    <th>No° Factura</th>
                                    <th>Total</th>
                                    <th>SKU</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($ventas as $venta)
                                    <tr>
                                        <td>{{ $venta->invoice_no }}</td>
                                        <td>{{ number_format($venta->final_total,2) }}</td>
                                        <td>{{ $venta->sku }}</td>
                                        <td>
                                            <a href="{{ route('facturas.generar', ['invoice_no' => $venta->invoice_no]) }}"
                                               class="btn btn-primary">Generar Factura</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación de los resultados (solo se muestra si hay resultados) -->
                    @if ($ventas->isNotEmpty())
                        <div class="d-flex justify-content-center">
                            {{ $ventas->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Escucha el evento de envío del formulario de búsqueda
            $('#searchForm').on('submit', function(event) {
                event.preventDefault(); // Evita el envío tradicional del formulario

                // Obtiene el valor del campo de búsqueda
                var searchInvoice = $('#searchInvoice').val();

                // Realiza la solicitud AJAX
                $.ajax({
                    url: '{{ route('facturas.mostrarVerVentas') }}', // Ruta del controlador para la búsqueda
                    method: 'GET',
                    data: {
                        search_invoice: searchInvoice, // Envía el valor del campo de búsqueda
                    },
                    success: function(response) {
                        // Actualiza la tabla de resultados con los nuevos datos
                        $('#ventasTable tbody').html(response);
                    },
                    error: function(xhr, status, error) {
                        // Maneja los errores de la solicitud AJAX si es necesario
                        console.log(xhr.responseText);
                    },
                });
            });
        });
    </script>
@endsection
