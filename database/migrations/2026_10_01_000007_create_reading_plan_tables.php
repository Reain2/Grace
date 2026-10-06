<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tradition_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('total_days');
            $table->string('source');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->index(['tradition_id', 'is_active']);
        });

        Schema::create('reading_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reading_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('day_number');
            $table->string('title');
            $table->text('reference');
            $table->timestamps();
            $table->unique(['reading_plan_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_plan_items');
        Schema::dropIfExists('reading_plans');
    }
};
