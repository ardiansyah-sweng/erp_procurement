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
        Schema::create('supplier_pic', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_id', 8);
            $table->string('pic_name', 100);
            $table->string('pic_telephone', 50);
            $table->string('pic_email', 100);
            $table->string('pic_assignment_date', 10);
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
        Schema::dropIfExists('supplier_pic');
    }
};
