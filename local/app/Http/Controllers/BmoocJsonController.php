<?php

namespace App\Http\Controllers;

use App\User;
use App\Artefact;
use App\Instruction;
use App\Tags;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use Redirect;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;

class BmoocJsonController extends Controller
{
	
	public function __construct() {
		//$this->middleware('auth', ['except' => 'index']);
	}
	
	public function discussion($id) {
		$artefact = Artefact::with(['type', 'the_author', 'last_modifier', 'active_instruction'])->find($id);
		$antwoorden = $artefact->answers;
		$tags = $artefact->tags;
		$instruction = Instruction::with(['available_types', 'instruction_type', 'the_author'])->where('thread', $artefact->thread)->where('active_from', '<=', date('Y-m-d H:i:s'))->where(function($q) { $q->whereNull('active_until')->orWhere('active_until', '>=', date('Y-m-d H:i:s')); })->get();
		$taglist = array();
		foreach ($tags as $tag) $taglist[] = $tag->id;
		$relatedDiscussions = DB::table('artefacts_tags')->whereIn('tag_id', $taglist)->where('artefact_id', '<>', $id)->distinct()->lists('artefact_id');
		$rels = Artefact::whereIn('id', $relatedDiscussions)->get();
		
		// Aantal antwoorden binnen de thread tellen
		$aantalAntwoorden = DB::table('artefacts')->where('thread', $artefact->thread)->count();
		
		return response()->json(['artefact' => $artefact, 'answers' => $antwoorden, 'aantalAntwoorden'=>$aantalAntwoorden, 'tags' => $tags, 'instruction' => $instruction, 'related'=>$rels]);
	}
	
	public function instruction($thread, $all = false) {
		$instruction = Instruction::with('instruction_type')->where('thread', $thread);
		if ($all) $instruction = $instruction->where('active_from', '<=', date('Y-m-d H:i:s'));
		$instruction = $instruction->get();
		return response()->json($instruction);
	}
	
	public function answers($id) {
        $parent = Artefact::with(['answers', 'type'])->find($id);
        $answers = array();
        array_push($answers, $parent);
        $answers = array_merge($answers, BmoocJsonController::buildList($parent));
        return response()->json($answers);
	}

    private function buildList($parent){
        $list = array();
        foreach($parent->answers as $child){
            $child = Artefact::with(['answers', 'type'])->find($child->id);
            array_push($list, $child);
            $list = array_merge($list, BmoocJsonController::buildList($child));
        }
        return $list;
    }
	

}
