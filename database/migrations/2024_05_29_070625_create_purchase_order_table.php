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
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->id();
            $table->string('po_number', 15);
            $table->string('item_id', 8);
            $table->string('item_name', 100);
            $table->string('category', 100);
            $table->string('uom', 25);
            $table->integer('quantity');
            $table->integer('price');
            $table->string('status', 15)->default('proposed');

            $table->string('grn_date', 10)->default('');
            $table->string('grn_condition', 20)->default('unverified');

            $table->index('po_number');
            $table->foreign('po_number')->references('po_number')->on('master_purchase_order')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('purchase_order');
    }
};
