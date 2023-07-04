<?php

namespace Modules\Facturacion\Entities;

use Illuminate\Database\Eloquent\Model;

class NegociosFacturacion extends Model
{
    protected $table = 'negocios_facturacion';

    protected $fillable = [
        'bussine_id',
        'bussine_name',
        'tradename',
        'rfc',
        'email',
        'telephone',
        'type_person',
        'taxregimen_id',
        'country_id',
        'state_id',
        'municipality_id',
        'location',
        'street',
        'colony',
        'zip',
        'no_exterior',
        'no_inside',
        'start_serie',
        'start_folio',
        'certificate',
        'key_private',
        'password',
        'name_pac',
        'password_pac',
        'production_pac',
        'logo',
        'data_api',
        'key',
        'advance',
        'prod_test',
        'key_prod'
    ];
}
