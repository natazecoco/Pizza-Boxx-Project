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
        Schema::create('size_crust_price', function (Blueprint $table) {
            $table->foreignId('size_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crust_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_increase', 8, 2)->default(0);
            $table->primary(['size_id', 'crust_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_crust_price');
    }
};
