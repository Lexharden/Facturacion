<?php

namespace Modules\Facturacion\Services;

use Illuminate\Support\Facades\DB;

class ApiKeyService
{
    public function obtenerApiKeyTest(array $businessLocationIds)
    {
        $apiKeys = DB::table('business as b')
            ->join('business_locations as bl', 'b.id', '=', 'bl.business_id')
            ->join('business_credentials as bc', 'bl.id', '=', 'bc.business_location_id')
            ->select('bc.key_test')
            ->whereIn('bl.id', $businessLocationIds)
            ->get();

        return $apiKeys ? $apiKeys->pluck('key_test')->all() : [];
    }

    public function obtenerApiKeyTestTodas($businessId)
    {
        $businessLocationIds = DB::table('business_locations as bl')
            ->join('business as b', 'bl.business_id', '=', 'b.id')
            ->select('bl.id')
            ->where('b.id', $businessId)
            ->get();

        if ($businessLocationIds->isNotEmpty()) {
            $apiKeys = [];
            foreach ($businessLocationIds as $businessLocationId) {
                $apiKey = $this->obtenerApiKeyTest([$businessLocationId->id]);
                if (!empty($apiKey)) {
                    $apiKeys[] = $apiKey[0];
                }
            }
            return $apiKeys;
        } else {
            return [];
        }
    }
}
