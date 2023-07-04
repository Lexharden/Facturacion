<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNegociosFacturacionTable extends Migration
{
    public function up()
    {
        Schema::create('negocios_facturacion', function (Blueprint $table) {
            $table->id();
            $table->string('bussine_id');
            $table->string('bussine_name');
            $table->string('tradename');
            $table->string('rfc');
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('type_person')->nullable();
            $table->string('taxregimen_id');
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('municipality_id')->nullable();
            $table->string('location')->nullable();
            $table->string('street')->nullable();
            $table->string('colony')->nullable();
            $table->string('zip');
            $table->string('no_exterior')->nullable();
            $table->string('no_inside')->nullable();
            $table->string('start_serie')->nullable();
            $table->string('start_folio')->nullable();
            $table->string('certificate')->nullable();
            $table->string('key_private')->nullable();
            $table->string('password')->nullable();
            $table->string('name_pac')->nullable();
            $table->string('password_pac')->nullable();
            $table->string('production_pac')->nullable();
            $table->string('logo')->nullable();
            $table->text('data_api')->nullable();
            $table->string('key')->nullable();
            $table->string('advance')->nullable();
            $table->string('prod_test')->nullable();
            $table->string('key_prod')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('negocios_facturacion');
    }
}

