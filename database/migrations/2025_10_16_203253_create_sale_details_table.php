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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->references('customer')
                ->nullable()->constrained()->nullOnDelete();

            $table->foreignId('payment_method_id')->references('payment_method')
                ->nullable()->constrained()->nullOnDelete();

            $table->foreignId('item_id')->references('item')
                ->nullable()->constrained()->nullOnDelete();

            $table->foreignId('salesItem_id')->references('salesItem')
                ->nullable()->constrained()->nullOnDelete();

            $table->decimal('subtotal', 10, 1);
            $table->decimal('tax', 10, 1)->default(0.0);
            $table->decimal('discount', 10, 1)->default(0.0);
            $table->decimal('total', 10, 1);
            $table->decimal('paid_amount', 10, 1);
            $table->decimal('change', 10, 1)->default(0.0);
            $table->string('status');

            $table->dateTime('sales_id')->references('sales')
                ->date_format('D, d, M, Y: h:i A')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_details');
    }
};
