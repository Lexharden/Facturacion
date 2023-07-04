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
    public function up(): void
    {
        Schema::create('business_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('business_location_id');
            $table->string('location_id');
            $table->string('organization_id');
            $table->string('key_live')->nullable();
            $table->string('key_test')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('business_credentials');
    }
};
