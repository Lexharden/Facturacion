<?php

namespace Modules\Facturacion\Services;

use Facturapi\Exceptions\Facturapi_Exception;
use Facturapi\Facturapi;
use Illuminate\Support\Facades\Storage;

class CertificatesService
{
    protected $facturapi;

    public function __construct($apiKey = null)
    {
        $this->facturapi = new Facturapi($apiKey ?: env('FACTURAPI_CERTIFICATES_KEY'));
    }

    public function uploadCertificate($organizationId, $cerContent, $keyContent, $password)
    {
        try {
            // Guardar los certificados temporalmente en el sistema de archivos
            $cerFileName = 'certificate.cer';
            $keyFileName = 'certificate.key';

            Storage::put($cerFileName, $cerContent);
            Storage::put($keyFileName, $keyContent);

            // Obtener las rutas absolutas de los archivos
            $cerAbsolutePath = Storage::path($cerFileName);
            $keyAbsolutePath = Storage::path($keyFileName);

            // Verificar si las rutas de los archivos son válidas
            if (!file_exists($cerAbsolutePath) || !file_exists($keyAbsolutePath)) {
                throw new \Exception('Rutas de archivos inválidas.');
            }

            // Enviar los certificados al servicio
            $organization = $this->facturapi->Organizations->uploadCertificate(
                $organizationId,
                [
                    'cerFile' => $cerAbsolutePath,
                    'keyFile' => $keyAbsolutePath,
                    'password' => $password,
                ]
            );
            //dd($organization);

            // Eliminar los certificados temporales
            Storage::delete([$cerFileName, $keyFileName]);

            return $organization;
        } catch (Facturapi_Exception $e) {
            $errorMessage = $e->getMessage();
            dd('Error en la función: ' . $errorMessage);
            return null;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            dd('Error en la función: ' . $errorMessage);
            return null;
        }
    }
}
