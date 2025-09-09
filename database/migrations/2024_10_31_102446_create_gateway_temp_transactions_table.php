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
        Schema::create('gateway_temp_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_gateway_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('type');
            $table->string('provider');
            $table->json('meta')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->foreign('access_gateway_id')->references('id')->on('access_gateways')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateway_temp_transactions');
    }
};
