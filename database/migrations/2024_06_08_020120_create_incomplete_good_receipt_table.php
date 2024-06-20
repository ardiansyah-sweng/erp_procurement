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
        Schema::create('incomplete_good_receipt', function (Blueprint $table) {
            $table->id();
            $table->string('po_number', 15);
            $table->string('item_id', 8);
            $table->string('name', 50);
            $table->smallInteger('quantity');
            $table->string('incomplete_img_path');
            $table->string('notes');
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
        Schema::dropIfExists('incomplete_good_receipt');
    }
};
