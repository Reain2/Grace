<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reminder_id')->constrained()->cascadeOnDelete();
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->string('channel')->default('web');
            $table->string('status')->default('sent');
            $table->timestamps();
            $table->unique(['reminder_id', 'delivery_date', 'delivery_time', 'channel'], 'reminder_delivery_dedup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_deliveries');
    }
};
