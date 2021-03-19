<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createalltables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('cpf')->unique();
            $table->string('password');
        });

        Schema::create('historic', function(Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('image');
        });

        Schema::create('linkmovie', function(Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
        });

        Schema::create('events', function(Blueprint $table) {
            $table->id();
            $table->string('img');
            $table->string('title');
            $table->string('description');
            $table->string('date');
        });

        Schema::create('projects', function(Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->string('name');
            $table->string('title');
            $table->text('description');
            $table->text('futureprojects');
            $table->date('datecreated');
            $table->string('cover');
            $table->text('photos');
        });

        Schema::create('projectsphotos', function(Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->integer('projectid');
            $table->string('path');
        });

        Schema::create('members', function(Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->string('name');
            $table->string('title');
            $table->text('description');
            $table->date('datecreated');
            $table->string('cover');
            $table->text('photos');
        });

        Schema::create('membersphotos', function(Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->integer('memberid');
            $table->string('path');
        });

        Schema::create('carsale', function(Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->string('name');
            $table->string('title');
            $table->string('description');
            $table->date('datecreated');
            $table->string('phone');
            $table->string('price');
            $table->string('cover');
        });

        Schema::create('carsalephotos', function(Blueprint $table) {
            $table->id();
            $table->integer('carsaleid');
            $table->integer('userid');
            $table->string('path');
        });

        Schema::create('partners', function(Blueprint $table) {
            $table->id();
            $table->string('photos');
            $table->string('title');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('historic');
        Schema::dropIfExists('linkmovie');
        Schema::dropIfExists('events');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('projectsphotos');
        Schema::dropIfExists('members');
        Schema::dropIfExists('membersphotos');
        Schema::dropIfExists('carsale');
        Schema::dropIfExists('carsalephotos');
    }
}
