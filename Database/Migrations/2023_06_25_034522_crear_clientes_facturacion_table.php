<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_facturacion', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name')->nullable(false);
            $table->string('tax_id')->nullable(false);
            $table->string('tax_system')->nullable(false);
            $table->integer('organization_id')->nullable(false);
            $table->integer('cliente_id')->nullable(false);
            $table->json('address')->nullable(false);
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes_facturacion');
    }
};
