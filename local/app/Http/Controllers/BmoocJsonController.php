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
		$antwoorden = $artefact->children;
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
	
    public function answers($id, $author = null, $tag = null, $keyword = null) {
		$parent = Artefact::all()->find($id);
        $parent = BmoocJsonController::search($parent, $author, $tag, $keyword);
        $tree = $parent;
        $parent->children = BmoocJsonController::buildTree($parent->children, $parent->id, $author, $tag, $keyword);
        return response()->json($tree);
	}

    public static function buildTree($elements, $parentId = 0, $author = null, $tag = null, $keyword = null) {
        $branch = array();
        foreach ($elements as $element) {
            $element = BmoocJsonController::search($element, $author, $tag, $keyword);
            if ($element['parent_id'] == $parentId) {
                $children = BmoocJsonController::buildTree($element->children, $element['id'], $author, $tag, $keyword);
                if ($children) {
                    //$element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    private static function search($element, $author, $tag, $keyword){
        if(isset($author) && $author != 'all'){
            if($element->author != $author) $element->hidden = true;
        }
        if(isset($tag) && $tag != 'all'){
            if(count($element->tags) <= 0){
                $element->hidden = true;
            } else {
                foreach($element->tags as $t){
                    if($t->id == $tag){
                        $element->hidden = null;
                        break;
                    }
                    if($t->id != $tag) $element->hidden = true;
                }
            }
        }
        if(isset($keyword)){
            if(stripos($element->title, $keyword) === false && stripos($element->contents, $keyword) === false){
                $element->hidden = true;
            }
        }
        return $element;
    }
	

}
