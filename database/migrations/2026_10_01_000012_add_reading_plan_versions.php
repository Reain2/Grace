<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reading_plans', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->after('is_active');
        });
        Schema::table('user_reading_plans', function (Blueprint $table) {
            $table->unsignedInteger('plan_version')->default(1)->after('reading_plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('user_reading_plans', fn (Blueprint $table) => $table->dropColumn('plan_version'));
        Schema::table('reading_plans', fn (Blueprint $table) => $table->dropColumn('version'));
    }
};
