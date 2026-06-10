<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('groupe')->nullable();
            $table->decimal('prix_supplementaire', 8, 2)->default(0);
            $table->boolean('obligatoire')->default(false);
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('options');
    }
};
