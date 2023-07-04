<?php

namespace Modules\Facturacion\Services;

use Facturapi\Exceptions\Facturapi_Exception;
use Facturapi\Facturapi;

class FacturapiService
{
    protected $facturapi;
    protected $facturapiOrganizacion;

    public function __construct($apiKey = null)
    {
        $apiKey = $apiKey ?? env('FACTURAPI_INVOICES_KEY'); // Valor por defecto si $apiKey es nulo
        $this->facturapi = new Facturapi($apiKey);
        $this->facturapiOrganizacion = new Facturapi(env('FACTURAPI_CERTIFICATES_KEY'));
    }

    public function getFacturapiInstance($apiKey = null)
    {
        $apiKey = $apiKey ?? env('FACTURAPI_INVOICES_KEY'); // Valor por defecto si $apiKey es nulo
        return new Facturapi($apiKey);
    }

    public function getFacturapiOrganizacionInstance()
    {
        return $this->facturapiOrganizacion;
    }

    public function getInvoices()
    {
        try {
            return $this->facturapi->Invoices->all();
        } catch (Facturapi_Exception $e) {
            // Manejar la excepción de manera adecuada
            return [];
        }
    }
}

