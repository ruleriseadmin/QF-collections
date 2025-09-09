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
        Schema::create('card_tokenizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_gateway_id');
            $table->unsignedBigInteger('customer_id');
            $table->longText('auth_token')->nullable();
            $table->string('provider');
            $table->string('expiry_month')->nullable();
            $table->string('expiry_year')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('access_gateway_id')->references('id')->on('access_gateways')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_tokenizations');
    }
};
