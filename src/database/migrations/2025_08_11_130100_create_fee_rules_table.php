<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fee_rules', function (Blueprint $t) {
            $t->id();
            $t->string('service_key');
            $t->decimal('from_amount', 18, 2);
            $t->decimal('to_amount', 18, 2);
            $t->enum('fee_type', ['fixed','percent']);
            $t->decimal('value', 18, 4);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index(['service_key','from_amount','to_amount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_rules');
    }
};
