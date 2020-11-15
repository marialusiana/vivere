<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVocherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vocher', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Voucher_Number', 50);
            $table->string('Voucher_Material', 50);
            $table->string('Basic_Material', 10);
            $table->timestamp('Valid_From')->nullable();
            $table->timestamp('Valid_To')->nullable();
            $table->timestamp('Reg_Date')->nullable();
            $table->string('Status', 10);
            $table->integer('Voucher_Value');
            $table->string('Currency', 10);
            $table->string('Voucher_Type', 10);
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
        Schema::dropIfExists('vocher');
    }
}
