<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model {
	protected $fillable = ['tag', 'times_used'];
	public function artefacts() {
		return $this->belongsToMany('App\Artefact', 'artefacts_tags', 'tag_id', 'artefact_id');
	}
}
