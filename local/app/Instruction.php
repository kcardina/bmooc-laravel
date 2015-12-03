<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model {

    public function instruction_type() {
        return $this->belongsTo('App\ArtefactType', 'instruction_type');
    }

    public function available_types() {
        return $this->belongsToMany('App\ArtefactType', 'instructions_artefact_types', 'instruction_id', 'artefact_type_id');
    }

    public function the_author() {
        return $this->belongsTo('App\User', 'author');
    }

    public static function getCurrent($thread) {
        return Self::with(['available_types', 'instruction_type'])->where('thread', $thread)->where('active_from', '<=', date('Y-m-d H:i:s'))->where(function($q) {
                    $q->whereNull('active_until')->orWhere('active_until', '>=', date('Y-m-d H:i:s'));
                })->get()->first();
    }

}
