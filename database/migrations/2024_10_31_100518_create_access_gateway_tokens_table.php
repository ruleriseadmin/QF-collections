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
        Schema::create('access_gateway_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_gateway_id');
            $table->string('access_id');
            $table->longText('token');
            $table->longText('test_token');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->foreign('access_gateway_id')->references('id')->on('access_gateways')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_gateway_tokens');
    }
};
