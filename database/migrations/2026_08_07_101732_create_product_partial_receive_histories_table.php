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
        if (!Schema::hasTable('product_partial_receive_histories')) {
            Schema::create('product_partial_receive_histories', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('transaction_id');
                $table->unsignedInteger('product_id');
                $table->unsignedInteger('purchase_line_id');
                $table->unsignedInteger('user_id');
                $table->float('purchase_quantity')->comment('Ordered quantity from supplier');
                $table->float('received_quantity')->comment('Partial quantity received from supplier');
                $table->text('note')->nullable()->comment('Comment if any');
                $table->dateTime('date')->comment('Date of product partial receive');
                $table->timestamps();
                $table->softDeletes();

                // Add foreign keys
                $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('purchase_line_id')->references('id')->on('purchase_lines')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_partial_receive_histories');
    }
};
