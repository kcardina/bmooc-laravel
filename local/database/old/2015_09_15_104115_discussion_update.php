<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiscussionUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artefacts', function($table) {
			$table->string('url')->nullable()->change();
			$table->text('body')->nullable()->change();
			$table->text('tags')->nullable()->change();
			$table->text('inhoud')->nullable()->change();
			$table->integer('vader_id')->nullable()->change();
			$table->string('titel')->nullable()->change();
		});//
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
