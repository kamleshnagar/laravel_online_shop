<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->decimal('discount', 10, 2);
            $table->decimal('grand_total', 10, 2);

            $table->string('payment_method'); // cod | stripe
            $table->string('payment_status')->default('pending');

            /* ================= Address ================= */
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');

            $table->foreignId('country_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('address');
            $table->string('apartment')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->text('order_notes')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
