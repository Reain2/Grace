<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password');
            $table->foreignId('tradition_id')->nullable()->after('role')->constrained('traditions')->nullOnDelete();
            $table->string('status')->default('active')->after('tradition_id');
            $table->string('verification_status')->default('pending')->after('status');
            $table->string('timezone', 40)->default('Asia/Jakarta')->after('verification_status');
            $table->timestamp('consent_at')->nullable()->after('timezone');
            $table->index(['role', 'tradition_id']);
            $table->index(['tradition_id', 'verification_status']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tradition_id']);
            $table->dropIndex(['role', 'tradition_id']);
            $table->dropIndex(['tradition_id', 'verification_status']);
            $table->dropColumn([
                'role',
                'tradition_id',
                'status',
                'verification_status',
                'timezone',
                'consent_at',
            ]);
        });
    }
};
