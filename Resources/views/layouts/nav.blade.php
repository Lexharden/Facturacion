<section class="no-print">
    <nav class="navbar navbar-default bg-white m-4">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('facturas') }}"><i class="fa fa-file"></i>Facturación</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Facturas <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('facturas.mostrarVerVentas')}}">Crear Factura</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Clientes <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('clientes.mostrarCreate') }}">Crear Cliente</a></li>
                            <li><a href="{{route('clientes.index')}}">Ver Clientes</a></li>
                        </ul>
                    </li>
                    @if(auth()->user()->can('superadmin'))
                        <li class="dropdown">
                            <a href="" class="dropdown-toggle" data-toggle="dropdown">Configuración<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href={{ route('configuracion') }}>Editar datos fiscales</a></li>
                                <li><a href={{ route('configuracion.corporativos') }}>Configurar negocios</a></li>
                                <li><a href={{ route('configuracion.upload_csd.form') }}>Subir certificado (CSD)</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</section>
