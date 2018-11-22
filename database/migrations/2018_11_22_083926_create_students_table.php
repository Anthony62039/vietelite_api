<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->string('first_name', 15);
            $table->string('last_name', 100);
            $table->date('dob');
            $table->enum('gender', ['nam','nữ']);
            $table->string('school', 100)->nullable();
            $table->string('class', 6)->nullable();
            $table->string('phone',12)->nullable();
            $table->string('email',100)->nullable();
            $table->string('avatar', 200)->nullable();
            $table->string('qr_code', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
