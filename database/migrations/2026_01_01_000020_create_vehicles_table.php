<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // La flotte appartient a GO'CAR : pas de owner_id / partner_id ici,
    // chaque vehicule est un actif propre de l'entreprise.
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('plate_number')->unique();
            $table->enum('category', ['berline', 'suv', '4x4', 'minibus', 'citadine'])->default('berline');
            $table->unsignedTinyInteger('seats')->default(4);
            $table->enum('transmission', ['manuelle', 'automatique'])->default('manuelle');
            $table->decimal('daily_price', 10, 2);
            $table->enum('status', ['disponible', 'en_location', 'maintenance', 'hors_service'])->default('disponible');
            $table->string('photo_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
