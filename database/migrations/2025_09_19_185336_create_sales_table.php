<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sales_items_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('subtotal', 10, 1);
            $table->decimal('tax', 10, 1)->default(0.0);
            $table->decimal('discount', 10, 1)->default(0.0);
            $table->decimal('total', 10, 1);
            $table->decimal('paid_amount', 10, 1);
            $table->decimal('change', 10, 1)->default(0.0);
            $table->string('status');
            $table->dateTime('sale_date')->useCurrent()->date_format('D, d, M, Y: h:i A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
