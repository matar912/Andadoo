<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Etend la table users par defaut de Laravel : un seul compte peut etre
    // client, chauffeur salarie ou administrateur, distingue par "role".
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->enum('role', ['client', 'driver', 'admin'])->default('client')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('locale', 5)->default('fr')->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'role', 'phone', 'locale']);
        });
    }
};
