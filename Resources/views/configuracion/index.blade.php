@extends('layouts.app')

@section('content')
    <div class="container">
        @include('facturacion::layouts.nav')
        <h1>Configuracion Fiscal</h1>

        <form method="POST" action="{{ route('configuracion.guardar') }}">
            @csrf
            <div class="form-group">
                <label for="name">Nombre Comercial <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="name" name="name" value="{{ $negociosFacturacion->bussine_name }}" required maxlength="100">
                </div>
            </div>

            <div class="form-group">
                <label for="legal_name">Razón Social <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="legal_name" name="legal_name" value="{{ $negociosFacturacion->tradename }}" required maxlength="100">
                </div>
            </div>

            <div class="form-group">
                <label for="tax_system">Régimen Fiscal <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <select class="form-control" id="tax_system" name="tax_system" required>
                        <option value="">Seleccionar Régimen Fiscal</option>
                        <option value="601" {{ $organization['tax_system'] == '601' ? 'selected' : '' }}>601 - General de Ley Personas Morales</option>
                        <option value="603" {{ $organization['tax_system'] == '603' ? 'selected' : '' }}>603 - Personas Morales con Fines no Lucrativos</option>
                        <option value="605" {{ $organization['tax_system'] == '605' ? 'selected' : '' }}>605 - Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
                        <option value="606" {{ $organization['tax_system'] == '606' ? 'selected' : '' }}>606 - Arrendamiento</option>
                        <option value="608" {{ $organization['tax_system'] == '608' ? 'selected' : '' }}>608 - Demás ingresos</option>
                        <option value="609" {{ $organization['tax_system'] == '609' ? 'selected' : '' }}>609 - Consolidación</option>
                        <option value="610" {{ $organization['tax_system'] == '610' ? 'selected' : '' }}>610 - Residentes en el Extranjero sin Establecimiento Permanente en México</option>
                        <option value="611" {{ $organization['tax_system'] == '611' ? 'selected' : '' }}>611 - Ingresos por Dividendos (socios y accionistas)</option>
                        <option value="612" {{ $organization['tax_system'] == '612' ? 'selected' : '' }}>612 - Personas Físicas con Actividades Empresariales y Profesionales</option>
                        <option value="614" {{ $organization['tax_system'] == '614' ? 'selected' : '' }}>614 - Ingresos por intereses</option>
                        <option value="616" {{ $organization['tax_system'] == '616' ? 'selected' : '' }}>616 - Sin obligaciones fiscales</option>
                        <option value="620" {{ $organization['tax_system'] == '620' ? 'selected' : '' }}>620 - Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
                        <option value="621" {{ $organization['tax_system'] == '621' ? 'selected' : '' }}>621 - Incorporación Fiscal</option>
                        <option value="622" {{ $organization['tax_system'] == '622' ? 'selected' : '' }}>622 - Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
                        <option value="623" {{ $organization['tax_system'] == '623' ? 'selected' : '' }}>623 - Opcional para Grupos de Sociedades</option>
                        <option value="624" {{ $organization['tax_system'] == '624' ? 'selected' : '' }}>624 - Coordinados</option>
                        <option value="628" {{ $organization['tax_system'] == '628' ? 'selected' : '' }}>628 - Hidrocarburos</option>
                        <option value="607" {{ $organization['tax_system'] == '607' ? 'selected' : '' }}>607 - Régimen de Enajenación o Adquisición de Bienes</option>
                        <option value="629" {{ $organization['tax_system'] == '629' ? 'selected' : '' }}>629 - De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales</option>
                        <option value="630" {{ $organization['tax_system'] == '630' ? 'selected' : '' }}>630 - Enajenación de acciones en bolsa de valores</option>
                        <option value="615" {{ $organization['tax_system'] == '615' ? 'selected' : '' }}>615 - Régimen de los ingresos por obtención de premios</option>
                        <option value="625" {{ $organization['tax_system'] == '625' ? 'selected' : '' }}>625 - Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas</option>
                        <option value="626" {{ $organization['tax_system'] == '626' ? 'selected' : '' }}>626 - Régimen Simplificado de Confianza</option>
                    </select>
                </div>
            </div>

            <hr>

            <h4>Dirección</h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="zip">Código Postal <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="zip" name="zip" value="{{ $negociosFacturacion->zip }}" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="street">Calle <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="street" name="street" value="{{ $organization['address']['street'] }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="exterior">Número Exterior <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="exterior" name="exterior" value="{{ $organization['address']['exterior'] }}" required>
                </div>
            </div>

            <!-- Otros campos de dirección si es necesario -->

            <hr>

            <div class="form-group">
                <label for="website">Sitio Web (Opcional) </label>
                <div class="col-md-6">
                    <input type="url" class="form-control" id="website" name="website" value="{{ $organization['website'] }}">
                </div>
            </div>

            <div class="form-group">
                <label for="support_email">Correo Electrónico(Opcional)</label>
                <div class="col-md-6">
                    <input type="email" class="form-control" id="support_email" name="support_email" value="{{ $organization['support_email'] }}">
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Teléfono (Opcional)</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $organization['phone'] }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
