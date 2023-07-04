<?php

namespace Modules\Facturacion\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Utils\ModuleUtil;
use Nwidart\Menus\Facades\Menu;

class DataController extends Controller
{

    public function user_permissions()
    {
        return [
            [
                'value' => 'facturacion.upload_csd.form',
                'label' => __('facturacion::lang.add_invoice'),
                'default' => false,
            ],
        ];
    }
    /**
     * Define el módulo como un paquete de superadministrador.
     * @return Array
     */
    public function superadmin_package()
    {
        return [
            [
                'name' => 'repair_module',
                'label' => __('repair::lang.repair_module'),
                'default' => false,
            ],
        ];
    }
    /**
     * Agrega los menús de facturación al menú de administración
     * @return null
     */
    public function modifyAdminMenu()
    {
        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();
        // $is_facturacion_enabled = (boolean)$module_util->hasThePermissionInSubscription($business_id, 'Facturacion_module');

        // if ($is_facturacion_enabled) {
        Menu::modify('admin-sidebar-menu', function ($menu) {
            $menu->url(
                action('\Modules\Facturacion\Http\Controllers\FacturacionController@index'),
                __('facturacion::lang.facturacion_menu'),
                ['icon' => 'fa fa-solid fa-receipt', 'active' => request()->segment(1) == 'facturacion', 'style' => config('app.env') == 'demo' ? 'background-color: #ff851b;' : '']
            )
                ->order(112);
        });
        // }
    }
}
