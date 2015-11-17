<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArtefactsTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
    	Schema::create('artefacts_tags', function (Blueprint $table) {
    		$table->integer('artefact_id');
    		$table->integer('tag_id');
    		$table->timestamps();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }
}
