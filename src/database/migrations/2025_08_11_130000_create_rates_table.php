<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $t) {
            $t->id();
            $t->string('base_currency')->default('IRR');
            $t->decimal('usd_buy', 18, 4);
            $t->decimal('usd_sell', 18, 4);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
