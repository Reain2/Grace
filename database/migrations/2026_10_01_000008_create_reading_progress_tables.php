<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_reading_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reading_plan_id')->constrained()->cascadeOnDelete();
            $table->date('started_on');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->unique(['user_id', 'reading_plan_id']);
        });

        Schema::create('reading_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_reading_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reading_plan_item_id')->constrained()->cascadeOnDelete();
            $table->date('checked_on');
            $table->timestamps();
            $table->unique(['user_reading_plan_id', 'reading_plan_item_id'], 'reading_progress_enrollment_item_unique');
            $table->index(['user_reading_plan_id', 'checked_on'], 'reading_progress_enrollment_date_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_progress');
        Schema::dropIfExists('user_reading_plans');
    }
};
