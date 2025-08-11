<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('level');
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->json('data_json')->nullable();
            $table->foreignId('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_profiles');
    }
};
