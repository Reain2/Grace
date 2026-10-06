<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holy_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tradition_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
            $table->index(['tradition_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holy_days');
    }
};
