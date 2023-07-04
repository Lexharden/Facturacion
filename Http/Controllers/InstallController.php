<?php

namespace Modules\Facturacion\Http\Controllers;

use App\System;
use Composer\Semver\Comparator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{
    public function __construct()
    {
        $this->module_name = 'facturacion';
        $this->appVersion = config('facturacion.module_version');
    }

    /**
     * Install
     *
     * @return Response
     */
    public function index()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $this->installSettings();

        //Check if facturacion installed or not.
        $is_installed = System::getProperty($this->module_name . '_version');
        if (!empty($is_installed)) {
            abort(404);
        }

        $action_url = action([\Modules\Facturacion\Http\Controllers\InstallController::class, 'install']);
        $instruction_type = 'uf';

        return view('install.install-module')
            ->with(compact('action_url', 'instruction_type'));
    }

    /**
     * Initialize all install functions
     */
    private function installSettings()
    {
        config(['app.debug' => true]);
        Artisan::call('config:clear');
    }

    /**
     * Installing Facturacion Module
     */
    public function install()
    {
        try {
            request()->validate([
                'license_code' => 'required',
                'login_username' => 'required',
            ], [
                'license_code.required' => 'License code is required',
                'login_username.required' => 'Username is required',
            ]);

            DB::beginTransaction();

            $license_code = request()->license_code;
            $email = request()->email;
            $login_username = request()->login_username;
            $pid = config('facturacion.pid');

            $is_installed = System::getProperty($this->module_name . '_version');
            if (!empty($is_installed)) {
                abort(404);
            }

            DB::statement('SET default_storage_engine=INNODB;');
            Artisan::call('module:migrate', ['module' => 'Facturacion', '--force' => true]);
            Artisan::call('module:publish', ['module' => 'Facturacion']);
            System::addProperty($this->module_name . '_version', $this->appVersion);

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => 'Facturacion module installed successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }

        return redirect()
            ->action([\App\Http\Controllers\Install\ModulesController::class, 'index'])
            ->with('status', $output);
    }

    /**
     * Uninstall
     *
     * @return Response
     */
    public function uninstall()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            System::removeProperty($this->module_name . '_version');

            $output = [
                'success' => true,
                'msg' => __('lang_v1.success'),
            ];
        } catch (\Exception $e) {
            $output = [
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }

    /**
     * Update module
     *
     * @return Response
     */
    public function update()
    {
        //Check if facturacion_version is same as appVersion then 404
        //If appVersion > facturacion_version - run update script.
        //Else there is some problem.
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '512M');

            $facturacion_version = System::getProperty($this->module_name . '_version');

            if (Comparator::greaterThan($this->appVersion, $facturacion_version)) {
                ini_set('max_execution_time', 0);
                ini_set('memory_limit', '512M');
                $this->installSettings();

                DB::statement('SET default_storage_engine=INNODB;');
                Artisan::call('module:migrate', ['module' => 'Facturacion', '--force' => true]);
                Artisan::call('module:publish', ['module' => 'Facturacion']);
                System::setProperty($this->module_name . '_version', $this->appVersion);
            } else {
                abort(404);
            }

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => 'Facturacion module updated successfully to version ' . $this->appVersion . '!!',
            ];

            return redirect()->back()->with(['status' => $output]);
        } catch (\Exception $e) {
            DB::rollBack();
            exit($e->getMessage());
        }
    }
}
