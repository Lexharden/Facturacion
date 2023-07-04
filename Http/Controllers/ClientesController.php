<?php

namespace Modules\Facturacion\Http\Controllers;

use Facturapi\Exceptions\Facturapi_Exception;
use Modules\Facturacion\Entities\ClientesFacturacion;
use Modules\Facturacion\Services\FacturapiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;


class ClientesController extends Controller
{
    protected FacturapiService $facturapiService;

    public function __construct(FacturapiService $facturapiService)
    {
        $this->facturapiService = $facturapiService;
    }

    public function mostrarCreate()
    {
        return view('facturacion::clientes.create');
    }

    public function index()
    {

        $business_id = request()->session()->get('user.business_id');
//        dd($business_id);
        $searchResult = $this->facturapiService->getFacturapiInstance()->Customers->all();
        $data = $searchResult->data;
//        dd($data);
        return view('facturacion::clientes.index', compact('data'));
    }


    public function store(Request $request)
    {
        $customerData = [
            'email' => $request->input('email'),
            'legal_name' => $request->input('legal_name'),
            'tax_id' => $request->input('tax_id'),
            'tax_system' => $request->input('tax_system'),
            'address' => [
                'zip' => $request->input('address.zip'),
                'street' => $request->input('address.street'),
                'exterior' => $request->input('address.exterior'),
                'interior' => $request->input('address.interior'),
                'neighborhood' => $request->input('address.neighborhood'),
                'city' => $request->input('address.city'),
                'municipality' => $request->input('address.municipality'),
                'state' => $request->input('address.state'),
                'country' => $request->input('address.country', 'MEX')
            ],
            'phone' => $request->input('phone')
        ];

        try {
            $customer = $this->facturapiService->getFacturapiInstance()->Customers->create($customerData);
            $cliente = new ClientesFacturacion();
            $cliente->legal_name = $request->input('legal_name');
            $cliente->tax_id = $request->input('tax_id');
            $cliente->tax_system = $request->input('tax_system');
            $addressJson = json_encode($customerData['address']);
            $cliente->address = $addressJson;
            $cliente->email = $request->input('email');
            $cliente->phone = $request->input('phone');
            $cliente->organization_id = $customer->organization;
            $cliente->cliente_id = $customer->id;
//            dd($customer);
            $cliente->save();
//            dd($customer);
            return redirect()->back()->with('success', 'Cliente creado exitosamente.');
        } catch (Facturapi_Exception $e) {

            return redirect()->back()->with('error', 'Error al crear el cliente: ' . $e->getMessage());
        }
    }

    public function obtenerClientes(Request $request)
    {

        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $page = 1;

        $startDateTime = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);

        $queryParams = [
            'q' => $search,
            'date' => json_encode([
                'date' => $startDateTime->format('Y-m-d\TH:i:s.vP') . '/' . $endDateTime->format('Y-m-d\TH:i:s.vP'),
            ]),
            'page' => $page
        ];
//        dd($queryParams);
        try {
            $searchResult = $this->facturapiService->getFacturapiInstance()->Customers->all($queryParams);
            $data = $searchResult->data;
//            dd($searchResult, $data);
            return view('facturacion::clientes.index', compact('data', 'search', 'startDate', 'endDate'));
        } catch (Facturapi_Exception $e) {
            return redirect()->back()->with('error', 'Error al obtener los clientes: ' . $e->getMessage());
        }

    }

    public function actualizarCliente(Request $request, $customerId)
    {
        $customerData = [
            'email' => $request->input('email'),
            'legal_name' => $request->input('legal_name'),
            'tax_id' => $request->input('tax_id'),
            'tax_system' => $request->input('tax_system'),
            'address' => [
                'street' => $request->input('address.street'),
                'exterior' => $request->input('address.exterior'),
                'interior' => $request->input('address.interior'),
                'neighborhood' => $request->input('address.neighborhood'),
                'city' => $request->input('address.city'),
                'municipality' => $request->input('address.municipality'),
                'zip' => $request->input('address.zip'),
                'state' => $request->input('address.state'),
                'country' => $request->input('address.country')
            ],
        ];
//        dd($customerData);

        try {
            $customer = $this->facturapiService->getFacturapiInstance()->Customers->update($customerId, $customerData);
//            dd($customer->message);
            if ($customer->message === 'RFC inválido') {
                return back()->with('error', 'RFC inválido');
            } else {
                return redirect()->back()->with('success', 'Cliente actualizado exitosamente.');
            }
        } catch (Facturapi_Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }
}
