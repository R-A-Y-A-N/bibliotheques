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
        Schema::create('livres', function (Blueprint $table) {
    $table->id();
    $table->string('titre');
    $table->text('description')->nullable();
    $table->integer('stock')->default(0);
    $table->string('image')->nullable();
    $table->integer('nombre_exmp')->default(0);
    $table->foreignId('auteur_id')->constrained()->cascadeOnDelete();
    $table->foreignId('categorie_id')->constrained()->cascadeOnDelete();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livres');
    }
};
