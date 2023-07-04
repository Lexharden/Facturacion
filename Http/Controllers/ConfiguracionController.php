<?php

namespace Modules\Facturacion\Http\Controllers;

use App\Http\Controllers\Controller;
use Facturapi\Exceptions\Facturapi_Exception;
use FontLib\Table\Type\loca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Facturacion\Services\FacturapiService;
use Modules\Facturacion\Services\CertificatesService;
use Modules\Facturacion\Entities\NegociosFacturacion;


class ConfiguracionController extends Controller
{
    protected FacturapiService $facturapiService;
    protected CertificatesService $certificatesService;
    protected FacturapiService $facturapiOrganizacionService;

    public function __construct(FacturapiService $facturapiService, CertificatesService $certificatesService, FacturapiService $facturapiOrganizacionService)
    {
        $this->facturapiService = $facturapiService;
        $this->certificatesService = $certificatesService;
        $this->facturapiOrganizacionService = $facturapiOrganizacionService;
    }

    public function index()
    {
        $negociosFacturacion = NegociosFacturacion::first();

        return view('facturacion::configuracion.index', compact('negociosFacturacion'));
    }


    public function configuracion()
    {
        return view('facturacion::configuracion.index');
    }

    public function mostrarVistaCsd()
    {
        if (!auth()->user()->can('facturacion.upload_csd')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
//        dd($business_id);
        return view('facturacion::configuracion.upload_csd');
    }


    public function guardar(Request $request)
    {
        // Validar los campos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'legal_name' => 'required|string|max:100',
            'tax_system' => 'required|string|size:3',
            'website' => 'nullable|string',
            'support_email' => 'nullable|string',
            'phone' => 'nullable|string',
            // Agrega aquí las validaciones para los campos de dirección si los tienes
        ]);

        // Realizar cualquier otra validación o manipulación de datos necesaria

        // Guardar la configuración en la base de datos o en otro lugar según tus necesidades
        // ...

        // Redirigir o retornar una respuesta según sea necesario
        return redirect()->back()->with('success', 'La configuración se ha guardado correctamente.');
    }


    public function uploadCSD(Request $request)
    {
        $organizationId = $request->input('organization_id');
        $cerFile = $request->file('cerFile');
        $keyFile = $request->file('keyFile');
        $password = $request->input('password');

        // Verificar si se seleccionaron los archivos correctamente
        if (!$cerFile || !$keyFile) {
            return back()->with('error', 'Por favor selecciona los archivos de certificado.');
        }

        // Obtener las rutas de almacenamiento para los archivos
        $cerFilePath = $cerFile->storeAs('certificates', 'certificate.cer');
        $keyFilePath = $keyFile->storeAs('certificates', 'certificate.key');

        // Verificar si los archivos se cargaron correctamente
        if (!$cerFilePath || !$keyFilePath) {
            return back()->with('error', 'Ocurrió un error al cargar los archivos.');
        }

        // Obtener las rutas absolutas de los archivos
        $cerAbsolutePath = Storage::path($cerFilePath);
        $keyAbsolutePath = Storage::path($keyFilePath);

        // Verificar si las rutas de los archivos son válidas
        if (!file_exists($cerAbsolutePath) || !file_exists($keyAbsolutePath)) {
            return back()->with('error', 'Rutas de archivos inválidas.');
        }

        // Obtener el contenido binario de los archivos
        $cerContent = file_get_contents($cerAbsolutePath);
        $keyContent = file_get_contents($keyAbsolutePath);

        // dd($cerContent,$keyContent);

        // Eliminar los archivos temporales subidos
        Storage::delete([$cerFilePath, $keyFilePath]);

        // Validar la longitud de la contraseña
        if (strlen($password) !== 8 && strlen($password) !== 16 && strlen($password) !== 24 && strlen($password) !== 32) {
            return back()->with('error', 'La contraseña debe tener 8, 16, 24 o 32 caracteres.');
        }

        // Llamar al método del servicio para subir los certificados
        try {
            $organization = $this->certificatesService->uploadCertificate($organizationId, $cerContent, $keyContent, $password);
//            dd($organization);
            /// Guardar los datos en la base de datos
            if ($organization->message === 'El Id de la organización no es válido') {
                return back()->with('error', 'Ocurrió un error al subir los certificados: ' . $organization->message);
            }
            if ($organization->message === 'La contraseña es incorrecta') {
                return back()->with('error', 'La contraseña proporcionada es incorrecta.');
            }
            $negociosFacturacion = new NegociosFacturacion();
            $negociosFacturacion->bussine_id = $organization->id;
            $negociosFacturacion->bussine_name = $organization->legal->name;
            $negociosFacturacion->tradename = $organization->legal->legal_name;
            $negociosFacturacion->rfc = $organization->legal->tax_id;
            $negociosFacturacion->email = $organization->legal->support_email;
            $negociosFacturacion->telephone = $organization->legal->phone;
            $negociosFacturacion->type_person = ''; // Agrega el valor correspondiente
            $negociosFacturacion->taxregimen_id = $organization->legal->tax_system;
            $negociosFacturacion->country_id = ''; // Agrega el valor correspondiente
            $negociosFacturacion->state_id = ''; // Agrega el valor correspondiente
            $negociosFacturacion->municipality_id = ''; // Agrega el valor correspondiente
            $negociosFacturacion->location = ''; // Agrega el valor correspondiente
            $negociosFacturacion->street = $organization->legal->address->street;
            $negociosFacturacion->colony = $organization->legal->address->neighborhood;
            $negociosFacturacion->zip = $organization->legal->address->zip;
            $negociosFacturacion->no_exterior = $organization->legal->address->exterior;
            $negociosFacturacion->no_inside = $organization->legal->address->interior;
            $negociosFacturacion->start_serie = ''; // Agrega el valor correspondiente
            $negociosFacturacion->start_folio = ''; // Agrega el valor correspondiente
            $negociosFacturacion->certificate = ''; // Agrega el valor correspondiente
            $negociosFacturacion->key_private = ''; // Agrega el valor correspondiente
            $negociosFacturacion->password = $password;
            $negociosFacturacion->name_pac = ''; // Agrega el valor correspondiente
            $negociosFacturacion->password_pac = ''; // Agrega el valor correspondiente
            $negociosFacturacion->production_pac = ''; // Agrega el valor correspondiente
            $negociosFacturacion->logo = $organization->logo_url;
            $negociosFacturacion->data_api = json_encode($organization);
            $negociosFacturacion->key = ''; // Agrega el valor correspondiente
            $negociosFacturacion->advance = ''; // Agrega el valor correspondiente
            $negociosFacturacion->prod_test = ''; // Agrega el valor correspondiente
            $negociosFacturacion->key_prod = ''; // Agrega el valor correspondiente
            $negociosFacturacion->created_at = now();
            $negociosFacturacion->updated_at = now();

            $negociosFacturacion->save();
            return back()->with('success', 'Los certificados se subieron correctamente.');
        } catch (Facturapi_Exception $e) {
            // Manejar la excepción en caso de error
            $errorMessage = $e->getMessage();
            return back()->with('error', 'Ocurrió un error al subir los certificados: ' . $errorMessage);
        }
    }

    public function obtenerCorporativosConfiguracion()
    {
        $corporativos = DB::table('business')
            ->select('business.id', 'business.name', 'business_locations.id as id_location', 'business_locations.location_id as prefix_location', 'business_locations.name as location_name')
            ->join('business_locations', 'business.id', '=', 'business_locations.business_id')
            ->get();
//        dd($corporativos);

        return view('facturacion::configuracion.corporativo', compact('corporativos'));
    }

    public function obtenerSucursalesConfiguracion(Request $request)
    {
//        dd($request->all());
        $id = $request->input('id');
        $datos = DB::table('business_locations')
            ->join('business', 'business_locations.business_id', '=', 'business.id')
            ->select('business_locations.*')
            ->where('business.id', $id)
            ->get();
//        dd($datos);
        return view('facturacion::configuracion.sucursal', compact('datos'));
    }

    public function crearOrganizacion(Request $request)
    {
//        dd($request);
        $name = $request->input('name');
        $businessId = $request->input('id');
        $locationId = $request->input('location_id');

        $organization = $this->facturapiOrganizacionService->getFacturapiOrganizacionInstance()
            ->Organizations->create([
                "name" => $name
            ]);
        $organizationId = $organization->id;
//        dd($businessId, $locationId, $organizationId);
        $testApiKey=$this->facturapiOrganizacionService->getFacturapiOrganizacionInstance()->Organizations->getTestApiKey(
            $organizationId
        );
//        dd($testApiKey,$organizationId);

        // Insertar los valores en la tabla business_credentials
        DB::table('business_credentials')->insert([
            'business_location_id' => $businessId,
            'location_id' => $locationId,
            'organization_id' => $organizationId,
            'key_live' => null,  // Agrega el valor correspondiente
            'key_test' => $testApiKey,  // Agrega el valor correspondiente
        ]);
        return redirect()->back()->with('success', 'La organización se ha creado correctamente.');
    }


}
