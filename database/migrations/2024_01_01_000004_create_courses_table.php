<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('credits');
            $table->string('level')->nullable();
            $table->string('schedule')->nullable();
            $table->string('classroom')->nullable();
            $table->integer('max_students')->default(0);
            $table->string('status')->default('active');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
