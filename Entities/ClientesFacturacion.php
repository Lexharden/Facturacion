<?php

namespace Modules\Facturacion\Entities;

use Illuminate\Database\Eloquent\Model;

class ClientesFacturacion extends Model
{
    protected $table = 'clientes_facturacion';
    protected $fillable = [
        'legal_name',
        'tax_id',
        'tax_system',
        'organization_id',
        'cliente_id',
        'address',
        'email',
        'phone',
    ];
}
