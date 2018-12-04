<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrolls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            $table->integer('parent_id');
            $table->string('subject', 13);
            $table->string('class', 2);
            $table->longText('note')->nullable();  
            $table->date('appointment')->nullable();
            $table->enum('appointment_status',['Chưa báo', 'Đã báo', 'Đã đến'])->default('Chưa báo');
            $table->integer('assign')->nullable();
            $table->date('assign_time')->nullable();
            $table->longText('result')->nullable();
            $table->enum('result_status',['Chưa báo', 'Đã báo','Cân nhắc','Chờ mở lớp','Chốt lớp','Học phụ đạo'])->default('Chưa báo');
            $table->integer('offical_class')->nullable();
            $table->date('first_day')->nullable();
            $table->enum('first_day_status',['Chưa báo', 'Đã báo','Đã đến']);
            $table->boolean('active')->default(true);
            $table->integer('receiver_id');
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
        Schema::dropIfExists('enrolls');
    }
}
