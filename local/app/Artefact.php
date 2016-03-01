<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artefact extends Model {

    public function the_author() {
        return $this->belongsTo('App\User', 'author');
    }

    public function last_modifier() {
        return $this->belongsTo('App\User', 'last_contributor');
    }

    public function children() {
        return $this->hasMany('App\Artefact', 'parent_id', 'id');
    }

    public function child_of() {
        return $this->belongsTo('App\Artefact', 'parent_id', 'id');
    }

    public function tags() {
        return $this->belongsToMany('App\Tags', 'artefacts_tags', 'artefact_id', 'tag_id');
    }

    public function type() {
        return $this->belongsTo('App\ArtefactType', 'artefact_type');
    }

    public function active_instruction() {
        return $this->belongsTo('App\Instruction', 'thread', 'thread')
            ->join('users', 'users.id', '=', 'instructions.author');
    }


}
