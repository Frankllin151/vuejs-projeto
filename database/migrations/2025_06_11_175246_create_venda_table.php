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
        Schema::create('venda', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cliente')->nullable();
            $table->string('email_cliente')->nullable();
            $table->foreignId('produto_id')->constrained('produto')->onDelete('cascade');
            $table->integer('quantidade');
            $table->decimal('total', 10, 2)->default(0);
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venda');
    }
};
