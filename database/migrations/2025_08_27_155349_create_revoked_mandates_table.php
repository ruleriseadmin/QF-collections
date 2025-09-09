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
        Schema::create('revoked_mandates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_gateway_id');
            $table->string('mandate_code')->unique();
            $table->string('customer_code');
            $table->string('email')->nullable();
            $table->string('status')->default('revoked');
            $table->timestamp('revoked_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revoked_mandates');
    }
};
