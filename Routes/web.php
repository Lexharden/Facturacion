<?php

use Modules\Facturacion\Http\Controllers\ClientesController;
use Modules\Facturacion\Http\Controllers\FacturacionController;
use Modules\Facturacion\Http\Controllers\ConfiguracionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'SetSessionData', 'auth', 'language', 'timezone', 'AdminSidebarMenu'])
    ->prefix('facturacion')->group(function () {
        Route::get('facturas', [FacturacionController::class, 'index'])->name('facturas');
        Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion');
        Route::post('configuracion/guardar', [ConfiguracionController::class, 'guardar'])->name('configuracion.guardar');
        Route::get('/configuracion/upload_csd', [ConfiguracionController::class, 'mostrarVistaCsd'])->name('configuracion.upload_csd.form');
        Route::put('/csd/upload', [ConfiguracionController::class, 'uploadCSD'])->name('csd.upload');
        Route::get('configuracion/corporativo', [ConfiguracionController::class, 'obtenerCorporativosConfiguracion'])->name('configuracion.corporativos');
        Route::get('configuracion/sucursal', [ConfiguracionController::class, 'obtenerSucursalesConfiguracion'])->name('configuracion.sucursal');
        Route::post('configuracion/crear-organizacion', [ConfiguracionController::class, 'crearOrganizacion'])->name('configuracion.crearOrganizacion');



        Route::get('/clientes/create', [ClientesController::class, 'mostrarCreate'])->name('clientes.mostrarCreate');
        Route::post('/clientes', [ClientesController::class, 'store'])->name('clientes.store');
        Route::get('/clientes/ver', [ClientesController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/obtener', [ClientesController::class, 'obtenerClientes'])->name('clientes.obtenerClientes');
        Route::put('/clientes/{customerId}', [ClientesController::class, 'actualizarCliente'])->name('clientes.actualizarCliente');

        Route::get('install', [\Modules\Facturacion\Http\Controllers\InstallController::class, 'index']);
        Route::post('install', [\Modules\Facturacion\Http\Controllers\InstallController::class, 'install']);
        Route::get('install/uninstall', [\Modules\Facturacion\Http\Controllers\InstallController::class, 'uninstall']);
        Route::get('install/update', [\Modules\Facturacion\Http\Controllers\InstallController::class, 'update']);

        // Nuevas rutas para facturas
        Route::get('facturas/ventas', [FacturacionController::class, 'mostrarVerVentas'])->name('facturas.mostrarVerVentas');
        Route::get('facturas/generar', [FacturacionController::class, 'mostrarFactura'])->name('facturas.generar');
        Route::post('facturas/facturar', [FacturacionController::class, 'timbrarFactura'])->name('facturas.facturar');
        Route::get('facturas/descargar/{invoiceId}', [FacturacionController::class, 'descargarFacturaZip'])->name('facturas.descargar');
        Route::post('/facturas/cancelar', [FacturacionController::class, 'cancelarFactura'])->name('facturas.cancelar');
        Route::post('/facturas/enviar-correo', [FacturacionController::class, 'enviarCorreo'])->name('facturas.enviarCorreo');

    });
