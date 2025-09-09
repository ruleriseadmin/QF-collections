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
        Schema::create('customer_virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('access_gateway_id');
            $table->string('provider');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('status');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('access_gateway_id')->references('id')->on('access_gateways')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_virtual_accounts');
    }
};
