<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temp_direct_debits', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('reference');
            $table->string('provider');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('access_gateway_id');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('access_gateway_id')->references('id')->on('access_gateways')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_direct_debits');
    }
};
