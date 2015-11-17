<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('instructions', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('thread');
			$table->date('active_from');
			$table->date('active_until')->nullable();
			$table->string('auteur');
			$table->string('title')->nullable();
			$table->string('type');
			$table->text('contents')->nullable();
			$table->string('url')->nullable();
			$table->string('types_available')->nullable();
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
        //
    }
}
