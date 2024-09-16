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
        Schema::create('validations', function (Blueprint $table) {

            $table->id();
            $table->string('email')->unique();
            $table->boolean('format')->default(false);
            $table->boolean('catchall')->default(false);
            $table->boolean('domain')->default(false);
            $table->boolean('noblock')->default(false);
            $table->boolean('nogeneric')->default(false);
            $table->string('status')->nullable();
            $table->integer('results')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validations');
    }
};
