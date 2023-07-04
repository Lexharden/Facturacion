@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @include('facturacion::layouts.nav')
                        <h1 class="card-title">Mostrar Factura</h1>
                    </div>

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

                    <form method="POST" action="{{ route('facturas.facturar') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">No° Factura:</label>
                                <div class="col-md-8">
                                    <input type="text" name="invoiceNo" class="form-control" value="{{ $invoiceNo }}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Conceptos:</label>
                                <div class="conceptos-container">
                                    <div class="row">
                                        @foreach ($productos as $producto)
                                            <div class="col-md-2">
                                                <strong>Cantidad:</strong> {{ number_format($producto->quantity,0) }}<br>
                                                <strong>Nombre:</strong> {{ $producto->name }}<br>
                                                <strong>SKU:</strong> {{ $producto->sku }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label>Total:</label>
                                <div class="total-container">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <strong> $ {{ number_format($producto->final_total, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="paymentForm">Forma de Pago</label>
                                <select class="form-control" id="paymentForm" name="payment_form">
                                    <option value="01">Efectivo</option>
                                    <option value="02">Cheque Nominativo</option>
                                    <option value="03">Transferencia Electrónica</option>
                                    <option value="04">Tarjeta de Crédito</option>
                                    <option value="05">Monedero Electrónico</option>
                                    <option value="06">Dinero Electrónico</option>
                                    <option value="08">Vales de Despensa</option>
                                    <option value="12">Dación en Pago</option>
                                    <option value="13">Subrogación</option>
                                    <option value="14">Consignación</option>
                                    <option value="15">Condónación</option>
                                    <option value="17">Compensación</option>
                                    <option value="23">Novación</option>
                                    <option value="24">Confusión</option>
                                    <option value="25">Remisión de Deuda</option>
                                    <option value="26">Prescripción o Caducidad</option>
                                    <option value="27">A Satisfacción del Acreedor</option>
                                    <option value="28">Tarjeta de Débito</option>
                                    <option value="29">Tarjeta de Servicios</option>
                                    <option value="99">Por Definir</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="invoiceType">Tipo de Factura</label>
                                <select class="form-control" id="invoiceType" name="invoice_type">
                                    <option value="G01">Adquisición de mercancías</option>
                                    <option value="G02">Devoluciones, descuentos o bonificaciones</option>
                                    <option value="G03">Gastos en general</option>
                                    <option value="I01">Construcciones</option>
                                    <option value="I02">Mobiliario y equipo de oficina por inversiones</option>
                                    <option value="I03">Equipo de transporte</option>
                                    <option value="I04">Equipo de computo y accesorios</option>
                                    <option value="I05">Dados, troqueles, moldes, matrices y herramental</option>
                                    <option value="I06">Comunicaciones telefónicas</option>
                                    <option value="I07">Comunicaciones satelitales</option>
                                    <option value="I08">Otra maquinaria y equipo</option>
                                    <option value="D01">Honorarios médicos, dentales y gastos hospitalarios</option>
                                    <option value="D02">Gastos médicos por incapacidad o discapacidad</option>
                                    <option value="D03">Gastos funerales</option>
                                    <option value="D04">Donativos</option>
                                    <option value="D05">Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)</option>
                                    <option value="D06">Aportaciones voluntarias al SAR</option>
                                    <option value="D07">Primas por seguros de gastos médicos</option>
                                    <option value="D08">Gastos de transportación escolar obligatoria</option>
                                    <option value="D09">Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones</option>
                                    <option value="D10">Pagos por servicios educativos (colegiaturas)</option>
                                    <option value="S01">Sin efectos fiscales</option>
                                    <option value="CP01">Pagos</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="customer">Seleccionar Cliente</label>
                                <select class="form-control" id="customer" name="customer">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach ($cliente_id as $customer)
                                        <option value="{{ $customer->_id }}">{{ $customer->legal_name }} ({{ $customer->tax_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Facturar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .conceptos-container {
            max-height: 200px;
            overflow-y: auto;
        }

        .conceptos-container .row {
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .conceptos-container .col-md-4 {
            flex: 0 0 auto;
            margin-right: 15px;
            white-space: normal;
        }
    </style>
@endpush
