<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verification_requests', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('status');
            $table->index(['user_id', 'is_active'], 'verification_user_active_index');
        });
    }

    public function down(): void
    {
        Schema::table('verification_requests', function (Blueprint $table) {
            $table->dropIndex('verification_user_active_index');
            $table->dropColumn('is_active');
        });
    }
};
