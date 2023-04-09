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
        Schema::create('task_in__boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_task')->constrained('tasks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_board')->constrained('boards')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('task_in__boards');
    }
};
