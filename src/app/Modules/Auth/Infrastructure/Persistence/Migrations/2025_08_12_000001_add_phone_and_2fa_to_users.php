<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');

            $table->boolean('is_admin')->default(false)->after('remember_token');

            $table->boolean('two_factor_enabled')->default(false)->after('is_admin');
            $table->timestamp('two_factor_passed_at')->nullable()->after('two_factor_enabled');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone','phone_verified_at','is_admin','two_factor_enabled','two_factor_passed_at']);
        });
    }
};
