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
        Schema::create('paymentlinks', function (Blueprint $table) {
            $table->id();
            $table->string('userid');
            $table->string('formid')->unique();
            $table->string('title');
            $table->string('description');
            $table->string('price');
            $table->timestamps();
        });

        Schema::create('paymentmade', function (Blueprint $table) {
            $table->id();
            $table->string('userid');
            $table->string('formid')->unique();
            $table->string('payer_name');
            $table->string('payer_email');
            $table->string('reference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymentlinks');
    }
};