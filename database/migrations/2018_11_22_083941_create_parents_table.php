<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('name_2' ,200)->nullable();
            $table->string('phone_1', 12);
            $table->string('phone_2', 40)->nullable();
            $table->string('parent_email', 200);
            $table->string('parent_email_2', 200)->nullable();
            $table->string('work', 200)->nullable();
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parents');
    }
}
