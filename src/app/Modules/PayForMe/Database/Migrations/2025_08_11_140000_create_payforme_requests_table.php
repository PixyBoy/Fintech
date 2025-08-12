<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payforme_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('request_code')->unique();
            $t->string('target_url');
            $t->decimal('amount_usd', 18, 2);
            $t->text('notes')->nullable();
            $t->json('attachments')->nullable();
            $t->json('quote_snapshot')->nullable();
            $t->unsignedBigInteger('order_id')->nullable();
            $t->string('status');
            $t->timestamps();
            $t->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payforme_requests');
    }
};
