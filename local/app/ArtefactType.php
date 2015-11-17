<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtefactType extends Model {
	public function artefacts() {
		return $this->hasMany('App\Artefact', 'artefact_type');
	}
	
	public function instructions() {
		return $this->hasMany('App\Instruction', 'instruction_type');
	}
}
