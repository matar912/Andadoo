<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('extra_price', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('option_reservation', function (Blueprint $table) {
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('option_id')->constrained()->cascadeOnDelete();
            $table->primary(['reservation_id', 'option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_reservation');
        Schema::dropIfExists('options');
    }
};
