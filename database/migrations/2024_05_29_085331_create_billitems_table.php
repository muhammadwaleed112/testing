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
        Schema::create('billitems', function (Blueprint $table) {
            $table->id();

            $table->string('unit');
            $table->decimal('price', 15, 2);
            $table->integer('qty');
            $table->decimal('sub_total', 15, 2);
            $table->unsignedBigInteger('pos_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('pos_id')->references('id')->on('pos')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billitems');
    }
};
