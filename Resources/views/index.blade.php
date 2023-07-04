@extends('layouts.app')

@section('content')
    <div class="container">
        @include('facturacion::layouts.nav') <!-- Aquí se incluye el archivo nav.blade.php -->
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

        <div class="table-responsive col-lg-12 offset-lg-2">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th scope="col" class="col-lg-2">Cliente receptor</th>
                    <th scope="col" class="col-lg-1">RFC</th>
                    <th scope="col" class="col-lg-2">UUID</th>
                    <th scope="col" class="col-lg-1">Total</th>
                    <th scope="col" class="col-lg-1">Estado</th>
                    <th scope="col" class="col-lg-2">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($invoices->data as $invoice)
                    <tr>
                        <td>{{ $invoice->customer->legal_name }}</td>
                        <td>{{ $invoice->customer->tax_id }}</td>
                        <td>{{ $invoice->uuid }}</td>
                        <td>$ {{ $invoice->total }}</td>
                        <td>
                            @if ($invoice->status === 'valid')
                                <span class="text-success">{{ trans("facturacion::lang.{$invoice->status}") }}</span>
                            @elseif ($invoice->status === 'canceled')
                                <span class="text-danger">{{ trans("facturacion::lang.{$invoice->status}") }}</span>
                            @else
                                {{ trans("facturacion::lang.{$invoice->status}") }}
                            @endif
                        </td>
                        <td>
                            <!-- Botón de descarga con el ID de la factura como parámetro -->
                            <a href="{{ route('facturas.descargar', ['invoiceId' => $invoice->id]) }}"
                               class="btn btn-primary btn-xs d-flex align-items-center justify-content-center rounded-circle text-center btn-icon"
                               style="width: 30px; height: 30px;">
                                <i class="fa fa-download fa-lg"></i>
                            </a>
                            <a href="#"
                               class="btn btn-warning btn-xs d-flex align-items-center justify-content-center rounded-circle text-center btn-icon"
                               style="width: 30px; height: 30px;"
                               data-toggle="modal"
                               data-target="#emailModal"
                               data-invoice-id="{{ $invoice->id }}">
                                <i class="fa fa-envelope fa-lg"></i>
                            </a>
                            <a href="#"
                               class="btn btn-danger btn-xs d-flex align-items-center justify-content-center rounded-circle text-center btn-icon"
                               style="width: 30px; height: 30px;"
                               data-toggle="modal"
                               data-target="#cancelModal"
                               data-invoice-id="{{ $invoice->id }}" data-cancel-reason="{{ $invoice->cancel_reason }}">
                                <i class="fa fa-times fa-lg"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para cancelar factura -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancelar Factura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="cancelForm" action="{{ route('facturas.cancelar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="invoiceId" id="cancelInvoiceId">
                        <div class="form-group">
                            <label for="cancelReason">Motivo de cancelación</label>
                            <select class="form-control" id="cancelReason" name="cancelReason" required>
                                <option value="01">Comprobante emitido con errores con relación.</option>
                                <option value="02">Comprobante emitido con errores sin relación.</option>
                                <option value="03">No se llevó a cabo la operación.</option>
                                <option value="04">Operación nominativa relacionada en la factura global.</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" form="cancelForm" class="btn btn-danger">Confirmar Cancelación</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para el envío de correo -->
    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Enviar Correo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para el envío de correo -->
                    <form id="emailForm" action="{{ route('facturas.enviarCorreo') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <input type="hidden" id="invoiceId" name="invoiceId" value="">
                        <!-- Otros campos adicionales según tus necesidades -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-send-email">Enviar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('js/cancel-modal.js') }}"></script>
<script src="{{ asset('js/email-modal.js') }}"></script>
