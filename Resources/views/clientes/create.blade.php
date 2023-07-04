@extends('layouts.app')

@section('content')
    <div class="container">
        @include('facturacion::layouts.nav')
        <h1>Crear Cliente</h1>

        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="legal_name">Nombre Fiscal o Razón Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="legal_name" name="legal_name" value="{{ old('legal_name') }}" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="tax_id">RFC o número de identificación tributaria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id') }}" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="tax_system">Clave del régimen fiscal <span class="text-danger">*</span></label>
                        <select class="form-control" id="tax_system" name="tax_system" required>
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

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                </div>
            </div>

            <hr>

            <h4>Domicilio Fiscal</h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Código Postal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address[zip]" value="{{ old('address.zip') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="street">Calle</label>
                        <input type="text" class="form-control" id="street" name="address[street]" value="{{ old('address.street') }}">
                    </div>

                    <div class="form-group">
                        <label for="exterior">Número Exterior</label>
                        <input type="text" class="form-control" id="exterior" name="address[exterior]" value="{{ old('address.exterior') }}">
                    </div>

                    <div class="form-group">
                        <label for="interior">Número Interior</label>
                        <input type="text" class="form-control" id="interior" name="address[interior]" value="{{ old('address.interior') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="neighborhood">Colonia</label>
                        <input type="text" class="form-control" id="neighborhood" name="address[neighborhood]" value="{{ old('address.neighborhood') }}">
                    </div>

                    <div class="form-group">
                        <label for="city">Ciudad</label>
                        <input type="text" class="form-control" id="city" name="address[city]" value="{{ old('address.city') }}">
                    </div>

                    <div class="form-group">
                        <label for="municipality">Municipio o Delegación</label>
                        <input type="text" class="form-control" id="municipality" name="address[municipality]" value="{{ old('address.municipality') }}">
                    </div>

                    <div class="form-group">
                        <label for="state">Estado</label>
                        <select class="form-control" id="state" name="address[state]">
                            <option value="">Seleccione un estado</option>
                            <option value="AGU">Aguascalientes</option>
                            <option value="BCN">Baja California</option>
                            <option value="BCS">Baja California Sur</option>
                            <option value="CAM">Campeche</option>
                            <option value="CHP">Chiapas</option>
                            <option value="CHH">Chihuahua</option>
                            <option value="DIF">Ciudad de México</option>
                            <option value="COA">Coahuila</option>
                            <option value="COL">Colima</option>
                            <option value="DUR">Durango</option>
                            <option value="MEX">Estado de México</option>
                            <option value="GUA">Guanajuato</option>
                            <option value="GRO">Guerrero</option>
                            <option value="HID">Hidalgo</option>
                            <option value="JAL">Jalisco</option>
                            <option value="MIC">Michoacán</option>
                            <option value="MOR">Morelos</option>
                            <option value="NAY">Nayarit</option>
                            <option value="NLE">Nuevo León</option>
                            <option value="OAX">Oaxaca</option>
                            <option value="PUE">Puebla</option>
                            <option value="QUE">Querétaro</option>
                            <option value="ROO">Quintana Roo</option>
                            <option value="SLP">San Luis Potosí</option>
                            <option value="SIN">Sinaloa</option>
                            <option value="SON">Sonora</option>
                            <option value="TAB">Tabasco</option>
                            <option value="TAM">Tamaulipas</option>
                            <option value="TLA">Tlaxcala</option>
                            <option value="VER">Veracruz</option>
                            <option value="YUC">Yucatán</option>
                            <option value="ZAC">Zacatecas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="country">País</label>
                        <select class="form-control" id="state" name="address[country]">
                            <option value="MEX">México</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
@endsection
