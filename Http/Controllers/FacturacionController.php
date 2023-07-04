<?php

namespace Modules\Facturacion\Http\Controllers;

use App\BusinessLocation;
use Facturapi\Exceptions\Facturapi_Exception;
use Faker\Container\ContainerException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Facturacion\Services\FacturapiService;
use Illuminate\Support\Facades\DB;
use Modules\Facturacion\Http\Controllers\ClientesController;


class FacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $facturapiService;
    protected $clientesController;

    public function __construct(FacturapiService $facturapiService, ClientesController $clientesController)
    {
        $this->facturapiService = $facturapiService;
        $this->clientesController = $clientesController;
    }

    public function index()
    {
        $invoices = $this->facturapiService->getInvoices();
        $invoice_id = $invoices->data;
//        dd($invoices);
        // Debug code
//        dd($invoice_id);
        return view('facturacion::index', compact('invoices', 'invoice_id'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */


    public function create()
    {
        return view('facturacion::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('facturacion::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('facturacion::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function mostrarVerVentas(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        $permitted_locations = auth()->user()->permitted_locations();

        // Realizar la consulta para obtener los productos vendidos
        $ventas = DB::table('transactions AS t')
            ->join('business AS b', 't.business_id', '=', 'b.id')
            ->join('business_locations AS bl', function ($join) use ($business_id, $permitted_locations) {
                $join->on('b.id', '=', 'bl.business_id');
                if ($permitted_locations != 'all') {
                    $join->whereIn('bl.id', $permitted_locations);
                }
            })
            ->join('transaction_sell_lines AS tsl', 't.id', '=', 'tsl.transaction_id')
            ->join('products AS p', function ($join) use ($business_id) {
                $join->on('b.id', '=', 'p.business_id')
                    ->on('tsl.product_id', '=', 'p.id');
            })
            ->select('t.invoice_no', 'tsl.quantity', 'p.name', 't.final_total', 'p.sku', 'b.id')
            ->where('b.id', $business_id);

        // Filtrar por número de transacción si se proporciona
        $searchInvoice = $request->input('search_invoice');
        if ($searchInvoice) {
            $ventas->where('t.invoice_no', 'LIKE', '%' . $searchInvoice . '%');
        }

        $ventas = $ventas->distinct()->paginate(10);

        return view('facturacion::facturas.crear', compact('ventas'));
    }


    public function mostrarFactura(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        $invoiceNo = $request->input('invoice_no');

        try {
            // Obtener los conceptos de la factura
            $productos = DB::table('transactions AS t')
                ->join('business AS b', 't.business_id', '=', 'b.id')
                ->join('transaction_sell_lines AS tsl', 't.id', '=', 'tsl.transaction_id')
                ->join('products AS p', function ($join) use ($invoiceNo) {
                    $join->on('b.id', '=', 'p.business_id')
                        ->on('tsl.product_id', '=', 'p.id');
                })
                ->select('tsl.quantity', 'p.name', 't.final_total', 'p.sku')
                ->where('t.invoice_no', $invoiceNo)
                ->where('b.id', $business_id)
                ->get();

            // Obtener la lista de clientes
            $customerResults = $this->facturapiService->getFacturapiInstance($apiKey)->Customers->all();
            $cliente_id = $customerResults->data;
//            dd($productos);

            // Pasa los conceptos y los clientes a la vista
            return view('facturacion::facturas.generar', compact('productos', 'invoiceNo', 'cliente_id'));
        } catch (Facturapi_Exception $e) {
            return redirect()->back()->with('error', 'Error al obtener los clientes: ' . $e->getMessage());
        }
    }


    public function timbrarFactura(Request $request)
    {
//        dd($request);
        // Obtener los valores del Request
        $business_id = request()->session()->get('user.business_id');
        $permitted_locations = auth()->user()->permitted_locations();
        $invoiceNo = $request->input('invoiceNo');
        $paymentForm = $request->input('payment_form');
        $invoiceType = $request->input('invoice_type');
        $customerId = $request->input('customer');
//        dd($permitted_locations);

        // Obtener los datos de las credenciales del negocio
        $credentials = DB::table('business_credentials')
            ->select('id', 'key_test')
            ->where('business_location_id', $business_id)
            ->first();
//        dd($credentials);

        try {
            // Verificar si se encontró el cliente
            if ($customerId) {
                // Realizar la consulta para obtener los productos relacionados con el número de factura
                $productos = DB::table('transactions AS t')
                    ->join('business AS b', 't.business_id', '=', 'b.id')
                    ->join('transaction_sell_lines AS tsl', 't.id', '=', 'tsl.transaction_id')
                    ->join('products AS p', function ($join) use ($invoiceNo) {
                        $join->on('b.id', '=', 'p.business_id')
                            ->on('tsl.product_id', '=', 'p.id');
                    })
                    ->select('tsl.quantity', 'p.name', 't.final_total', 'p.sku')
                    ->where('t.invoice_no', $invoiceNo)
                    ->where('b.id', $business_id)
                    ->get();
//                dd($productos);

                // Construir el arreglo de datos para la factura
                $invoiceData = [
                    "customer" => $customerId,
                    "items" => $productos->map(function ($producto) {
                        return [
                            "quantity" => $producto->quantity,
                            "product" => [
                                "description" => $producto->name,
                                "product_key" => "43211507",
                                "price" => $producto->final_total,
                                "sku" => $producto->sku
                            ]
                        ];
                    })->toArray(),
                    "payment_form" => $paymentForm,
                    "folio_number" => $invoiceNo,
                    "series" => "F",
                    "use" => $invoiceType
                ];
//                dd($invoiceData);

                // Facturar usando los datos obtenidos
                $invoice = $this->facturapiService->getFacturapiInstance()->Invoices->create($invoiceData);
                dd($invoice);

                // Añadir un mensaje de éxito para indicar que la factura fue timbrada con éxito
                return back()->with('success', 'La factura se timbró y se creó correctamente.');
            } else {
                // El cliente no existe, mostrar mensaje de error o redirigir a una página de error
                return back()->with('error', 'Error al encontrar el cliente.');
            }
        } catch (Facturapi_Exception $e) {
            // Manejar la excepción en caso de error
            return back()->with('error', 'Ocurrió un error al facturar: ' . $e->getMessage());
        }
    }


    public function descargarFacturaZip($invoiceId)
    {
        try {
            $zip = $this->facturapiService->getFacturapiInstance()->Invoices->download_zip($invoiceId);
            $file_name = $invoiceId . '.zip';

            // Devolver la respuesta con el archivo ZIP
            return response($zip)
                ->header('Content-Type', 'application/zip')
                ->header('Content-Disposition', 'attachment; filename="' . $file_name . '"');
        } catch (Facturapi_Exception $e) {
            // Manejar la excepción en caso de error al descargar la factura
            return back()->with('error', 'Ocurrió un error al descargar la factura: ' . $e->getMessage());
        }
    }

    public function cancelarFactura(Request $request)
    {
        try {

            $invoiceId = $request->input('invoiceId');
            $cancelReason = $request->input('cancelReason');
//            dd($invoiceId,$cancelReason);
            // Cancelar la factura utilizando Facturapi
            $facturapi = $this->facturapiService->getFacturapiInstance();
            $response = $facturapi->Invoices->cancel($invoiceId, [
                "motive" => $cancelReason // Motivo de cancelación
            ]);
//            dd($response, $cancelReason);

            // Verificar la respuesta de Facturapi
            if ($response->status === 'canceled') {

                // Factura cancelada exitosamente, puedes realizar las acciones necesarias (actualizar base de datos, mostrar mensaje, etc.)
                return back()->with('success', 'La factura ha sido cancelada exitosamente.');
            } else {
                // Error al cancelar la factura, muestra un mensaje de error
                return back()->with('error', 'Error al cancelar la factura: ' . $response->message);
            }
        } catch (Facturapi_Exception $e) {
            // Manejar cualquier excepción generada por Facturapi
            return back()->with('error', 'Error al cancelar la factura: ' . $e->getMessage());
        }
    }

    public function enviarCorreo(Request $request)
    {
        try {
            $invoiceId = $request->input('invoiceId');
            $email = $request->input('email');

            // Verificar si la factura existe
            if (!$invoiceId) {
                abort(404, 'Factura no encontrada');
            }

            // Obtener la instancia de Facturapi
            $facturapi = $this->facturapiService->getFacturapiInstance();

            // Enviar la factura por correo
            $response = $facturapi->Invoices->send_by_email($invoiceId, $email);
            dd($response->ok);

            // Verificar la respuesta de Facturapi
            if ($response->ok == true) {
                // El correo se envió exitosamente
                return back()->with('success', 'El correo se envió exitosamente');
            } else {
                // Error al enviar el correo
                return back()->with('error', 'Error al enviar el correo: ' . $response->message);
            }
        } catch (Facturapi_Exception $e) {
            // Manejar cualquier excepción generada por Facturapi
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }


}



