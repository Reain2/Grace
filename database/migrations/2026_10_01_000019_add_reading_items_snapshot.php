<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_reading_plans', function (Blueprint $table) {
            $table->json('items_snapshot')->nullable()->after('plan_version');
        });
    }

    public function down(): void
    {
        Schema::table('user_reading_plans', fn (Blueprint $table) => $table->dropColumn('items_snapshot'));
    }
};
