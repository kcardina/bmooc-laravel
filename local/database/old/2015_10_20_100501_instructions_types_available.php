<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstructionsTypesAvailable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('instructions', function (Blueprint $table) {
			$table->dropColumn('types_available');
		});
		
		Schema::create('instructions_types', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('instructions_id');
			$table->string('type_available');
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
