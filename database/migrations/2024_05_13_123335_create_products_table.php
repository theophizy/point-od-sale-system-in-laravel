<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('barcode')->nullable();
            $table->integer('quantity');
            $table->string('weight');
            $table->string('manufacture_date');
            $table->string('expiry_date');
            $table->string('manufacturer');
            $table->enum('sold_in', ['PACKS', 'UNITS'])->default('PACKS');
            $table->string('status')->default('ACTIVE');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
