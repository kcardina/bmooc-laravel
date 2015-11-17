<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BmoocGeneral extends Migration {
	public function up() {
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('email')->nullable()->unique();
			$table->string('password', 60);
			$table->string('username')->nullable();
			$table->string('avatar');
			$table->string('role')->nullable();
			$table->string('provider');
			$table->string('provider_id')->unique();
			$table->rememberToken();
			$table->timestamps();
        });
		Schema::create('password_resets', function (Blueprint $table) {
			$table->string('email')->index();
			$table->string('token')->index();
			$table->timestamp('created_at');
		});
		Schema::create('artefact_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('description');
			$table->timestamps();
		});
		Schema::create('tags', function (Blueprint $table) {
			$table->increments('id');
			$table->string('tag');
			$table->integer('times_used');
			$table->timestamps();
		});
    Schema::create('artefacts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('parent_id')->nullable();
			$table->integer('thread')->unsigned();
			$table->string('author')->nullable();
			$table->string('artefact_type');
			$table->string('title')->nullable();
			$table->text('contents')->nullable();
			$table->string('url')->nullable();
			$table->string('attachment')->nullable();
			$table->timestamps();
		});
		Schema::create('artefacts_tags', function (Blueprint $table) {
			$table->integer('artefact_id');
			$table->integer('tag_id');
			$table->timestamps();
		});
		Schema::create('instructions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('thread');
			$table->dateTime('active_from');
			$table->dateTime('active_until')->nullable();
			$table->string('author');
			$table->string('instruction_type');
			$table->string('title')->nullable();
			$table->text('contents')->nullable();
			$table->string('url')->nullable();
			$table->timestamps();
		});
		Schema::create('instructions_artefact_types', function(Blueprint $table) {
			$table->integer('instruction_id');
			$table->integer('artefact_type_id');
		});
    }
    public function down() {
    	Schema::drop('users');
    	Schema::drop('password_resets');
    	Schema::drop('artefacts');
	}
}
