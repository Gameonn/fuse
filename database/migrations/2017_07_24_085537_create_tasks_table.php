<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->integer('milestone_id');
            $table->integer('sprint_id');
            $table->string('task_name');
            $table->text('task_description');
            $table->integer('assign_to');
            $table->integer('percentage_completed');
            $table->integer('alloted_hours');
            $table->integer('hours_spent');
            $table->tinyInteger('priority');
            $table->tinyInteger('status');
            $table->tinyInteger('remark');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('tasks');
    }
}
