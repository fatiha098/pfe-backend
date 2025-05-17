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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('department', [
                            'Informatique',
                            'Gestion des entreprises',
                            'Commerce et Vente',
                            'Réseaux et Télécommunications',
                            'Maintenance Industrielle',
                            'Électromécanique',
                            'Génie Civil',
                            'Audiovisuel et Multimédia',
                        ])->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
