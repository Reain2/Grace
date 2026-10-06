<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tradition_id')->constrained()->restrictOnDelete();
            $table->text('body');
            $table->string('source');
            $table->date('scheduled_for')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['tradition_id', 'scheduled_for']);
            $table->index(['tradition_id', 'is_active']);
        });

        Schema::create('quote_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'quote_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_bookmarks');
        Schema::dropIfExists('quotes');
    }
};
