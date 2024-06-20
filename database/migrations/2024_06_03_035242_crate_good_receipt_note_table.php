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
        Schema::create('good_receipt_note', function (Blueprint $table) {
            $table->id();
            $table->string('po_number', 15);
            $table->string('item_id', 8);
            $table->string('condition', 2)->default('1');
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
        Schema::dropIfExists('good_receipt_note');
    }
};