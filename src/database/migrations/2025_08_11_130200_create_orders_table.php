<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('service_key');
            $t->string('currency')->default('USD');
            $t->decimal('amount_usd', 18, 2);
            $t->decimal('fee_usd', 18, 4);
            $t->decimal('subtotal_usd', 18, 4);
            $t->decimal('rate_used', 18, 4);
            $t->decimal('total_irr', 20, 2);
            $t->json('meta')->nullable();
            $t->json('quote_breakdown');
            $t->string('status');
            $t->timestamps();
            $t->index(['service_key', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
