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
        Schema::create('sub_in__tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_subtask')->constrained('sub_tasks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_task')->constrained('tasks')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sub_in__tasks');
    }
};
