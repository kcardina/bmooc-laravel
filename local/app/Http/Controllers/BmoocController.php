<?php

namespace App\Http\Controllers;

use App\User;
use App\Artefact;
use App\ArtefactType;
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
use Carbon\Carbon;

class BmoocController extends Controller
{
	
	public function __construct() {
		//$this->middleware('auth', ['except' => 'index']);
	}
	public function index(Request $request) {
		//$user = Auth::user();
		$user = $request->user();
		//dd($request);
		$topics = Artefact::with(['the_author', 'tags', 'last_modifier'])->whereNull('parent_id')->orderBy('created_at', 'desc')->orderBy('last_modified', 'desc')->get();
		$auteurs = DB::table('users')->select('id', 'name')->distinct()->get();
		$tags = Tags::orderBy('tag')->get();

		$aantalAntwoorden = DB::table('artefacts')->select(DB::raw('count(*) as aantal_antwoorden, thread'))
                     ->groupBy('thread')->get();
		return view('index', ['topic'=>$topics, 'user'=>$user, 'auteurs' => $auteurs, 'tags' => $tags, 'aantalAntwoorden'=>$aantalAntwoorden]);
	}
	
	public function help() {
		$user = Auth::user();
		return view('help', ['user'=>$user]);
	}
	
	public function showDiscussionEncoded($encodedLink='')
	{
		$link = explode('/', base64_decode($encodedLink));
		if (count($link) == 3) return $this->showDiscussion($link[0], $link[1], $link[2], false);
		else return view ('index_login');
	}
	
	public function showTopic($links, $answer=null) {
		$user = Auth::user();
		return view('topic', ['artefactLeft' => $links, 'answerRight' => $answer, 'user'=>$user]);
	}
	
	public function showDiscussion($links, $rechts, $pretrail = '', $encodeLink = false)
	{
		$user = Auth::user();
		//if (!$user) return view('index_login');
		
		$linksArray = explode('_', $links); // ID_antwoordnr
		$trailArray = explode('$', $pretrail); // array van ID_antwoordnr
		$pretrail = '';
		$artefactLinks = Artefact::find($linksArray[0]); // De gevraagde discussie voor linker vak
		
		if ($artefactLinks) {
			$anchors = array();
			
			$artefactRechts = null;
			if (count($artefactLinks->antwoorden) > $rechts) $artefactRechts = $artefactLinks->antwoorden[$rechts];

			if ($artefactLinks->hoofd)
				$anchors['hoofd'] = $artefactLinks->hoofd->id.'_0/'.$linksArray[1].'/'.$links.'$'.$pretrail;
			else $anchors['hoofd'] = null;
			
			if ($artefactRechts && count($artefactRechts->antwoorden)>0) {
				$anchors['volgende'] = $artefactRechts->id.'_'.$rechts.'/0/'.$links.'$'.$pretrail;
			} elseif ($artefactRechts && count($artefactRechts->antwoorden) == 0) {
				$anchors['volgende'] = $artefactRechts->id.'_'.$rechts.'/0/'.$links.'$'.$pretrail;
			} else $anchors['volgende'] = null;
			
			if ($rechts > 0) // Het artefact rechts is niet het eerste kind, dus link bovenaan tonen
				$anchors['boven'] = $links.'/'.($rechts-1).'/'.$links.'$'.$pretrail;
			else $anchors['boven'] = null;
			
			if (count($artefactLinks->antwoorden) > $rechts+1) // Er is nog een antwoord na het rechtse
				$anchors['onder'] = $links.'/'.($rechts+1).'/'.$links.'$'.$pretrail;
			else $anchors['onder'] = null;

			// Tags ophalen en gerelateerde discussies opzoeken op basis van de tags
			//$tags = explode(',', $artefactLinks->tags);	
			//$relatedDiscussions = Artefact::where('tags', 'rlike', implode('|',$tags))->where('id', '<>', $artefactLinks)->where('thread', '<>', $artefactLinks->thread)->get();
			$relatedDiscussions = DB::table('artefacts_tags')->whereIn('tag_id', $taglist)->where('artefact_id', '<>', $id)->distinct()->lists('artefact_id');
			$rels = Artefact::whereIn('id', $relatedDiscussions)->get();
			$relatedLinks = array();
			foreach ($rels as $relatedd)
			{
				$i = 0;
				if ($relatedd->child_of) {
					$h = $relatedd->child_of;
					foreach ($h->answers as $antwoord) {
						if ($antwoord->id == $relatedd->id) break;
						$i++;
					}
				} 
				$link = $relatedd->id.'_'.$i.'/0/';
				$link = $encodeLink?base64_encode($link):$link;
				$relatedLinks[] = array('titel'=>$relatedd->title, 'tags'=>$relatedd->tags, 'link'=>$link);
			}
			
			// Alle tags in de thread
			$alleTags = DB::table('artefacts')->where('thread', $artefactLinks->thread)->get(['tags']);
			$tagarray = array();
			foreach ($alleTags as $t) {
				$tag = explode(',', $t->tags);
				array_walk($tag, function(&$item, $key) {$item = trim($item); });
				$tagarray = array_merge($tagarray, $tag);
			}
			if ($encodeLink) foreach ($anchors as $key => $item) $anchors[$key] = base64_encode($item);
			
			return view('discussion', ['artefactLinks' => $artefactLinks, 'artefactRechts' => $artefactRechts, 'related' => $relatedLinks, 'anchors' => $anchors, 'alletags' => array_unique($tagarray),'user' => $user]);
		}
	}
	
	public function searchDiscussionsByTag($tag) {
		$user = Auth::user();
		$discussies = DB::table('artefacts_tags')->leftJoin('tags', 'tag_id', '=', 'tags.id')
			->leftJoin('artefacts', 'artefact_id', '=', 'artefacts.id')
			->where('tags.id', $tag)->select('thread')->distinct()->lists('thread');//Artefact::with(['the_author', 'tags', 'last_modifier'])->where('tags', 'like', '%'.$tag.'%')->get();
		$discs = Artefact::whereIn('thread', $discussies)->whereNull('parent_id')->orderBy('last_modified')->get();
		//dd($discussies);
		$auteurs = DB::table('users')->select('name')->distinct()->get();
		$tags = Tags::orderBy('tag')->get();
		$aantalAntwoorden = DB::table('artefacts')->select(DB::raw('count(*) as aantal_antwoorden, thread'))
                     ->groupBy('thread')->get();
				
		//$filtered = $discussies->filter(function ($item) { 
		//	return $item->vader_id == null; })->all();
		return view('index', ['topic'=>$discs, 'user'=>$user, 'auteurs' => $auteurs, 'tags' => $tags, 'titel'=>"met tag '".$tag."'", 'aantalAntwoorden'=>$aantalAntwoorden]);
	}
	
	public function searchDiscussionsByAuthor($author) {
		$user = Auth::user();
		$discussies = Artefact::with(['the_author', 'tags', 'last_modifier'])->where('author', $author)->get();
		$auteurs = DB::table('users')->select('name')->distinct()->get();
		$tags = Tags::orderBy('tag')->get();
		
		$aantalAntwoorden = DB::table('artefacts')->select(DB::raw('count(*) as aantal_antwoorden, thread'))
                     ->groupBy('thread')->get();
				
		return view('index', ['topic'=>$discussies, 'user'=>$user, 'titel'=>"van auteur ".$author, 'auteurs' => $auteurs, 'tags' => $tags, 'aantalAntwoorden'=>$aantalAntwoorden]);
	}

    public function searchDiscussions($author = null, $tag = null, $keyword = null){
        $user = Auth::user();
        //filter the artefacts on author first
        $discussies = DB::table('artefacts');
        if(isset($author) && $author != "all"){
            $discussies
                ->where('author', $author);
        }
        // tags
        if(isset($tag) && $tag != "all"){
            $discussies
                ->join('artefacts_tags', 'artefacts.id', '=', 'artefacts_tags.artefact_id')
                ->join('tags', 'artefacts_tags.tag_id', '=', 'tags.id')
                ->where('tag_id', $tag);
        }
        // query
        if(isset($keyword)){
            $discussies
                ->where(function($q) use( &$keyword)
                {
                    $q
                        ->where('title', 'LIKE', '%'.$keyword.'%')
                        ->orWhere('contents', 'LIKE', '%'.$keyword.'%');
                });
        }
        // the current implementation is to return treads, not artefacts
        $discussies = $discussies
            ->select('thread')
            ->distinct()
            ->lists('thread');
        // SORT?
            //->distinct();
        $discs = Artefact::whereIn('thread', $discussies)
            ->whereNull('parent_id')
            ->orderBy('last_modified')
            ->get();
        // extra information needed
        $auteurs = DB::table('users')->select('name', 'id')->distinct()->get();
		$tags = Tags::orderBy('tag')->get();
        $aantalAntwoorden = DB::table('artefacts')
            ->select(DB::raw('count(*) as aantal_antwoorden, thread'))
            ->groupBy('thread')->get();

        //dd("Your search for tag: " . $tag . ", author: " . $author . " and keyword: " . $query . " returned " . $discussies->count() . " results");
        //dd($discs);




        return view('index', ['topic'=>$discs, 'user'=>$user, 'auteurs' => $auteurs, 'tags' => $tags, 'titel'=>"met tag '".$tag."'", 'aantalAntwoorden'=>$aantalAntwoorden, 'search'=>['tag'=>$tag, 'author'=>$author, 'keyword'=>$keyword]]);
    }
	
	public function commentDiscussion(Request $request)
	{
		$user = Auth::user();
		//dd($request);
		if ($user) { // Als de gebruiker ingelogd is, anders niets doen
			$comment = new Artefact();
			$comment->author = $user->id;
			
			// Titel van het artefact zetten
			if ($request->input('answer_title')) $comment->title = $request->input('answer_title');
			else $comment->title = 'No title';
			
			switch ($request->input('answer_temp_type')) {
				case 'text':
					if ($request->input('answer_text')) {
						$comment->contents = $request->input('answer_text');
						//$instruction->instruction_type = 'text';
					}
					$at = ArtefactType::where('description', 'text')->first();
					if ($at) $at->artefacts()->save($comment);
					break;
				case 'url':
					if ($request->input('answer_url')) $comment->url = $request->input('answer_url');
					else $comment->url = 'https://www.youtube.com/embed/YecyKnQUcBY'; // Dummy video
					//$instruction->instruction_type = null;
					$url = $comment->url;
					if (strpos($url, 'youtube') !== false) { // Youtube video
						if (strpos($url, 'watch?v=')) $comment->url = 'http://www.youtube.com/embed/'.substr($url, strpos($url, 'watch?v=') + 8);
						//$instruction->instruction_type = 'video_youtube';
						$at = ArtefactType::where('description', 'video_youtube')->first();
					} elseif (strpos($url, 'vimeo') !== false) { // Vimeo video
						//$instruction->instruction_type = 'video_vimeo';
						$at = ArtefactType::where('description', 'video_vimeo')->first();
					} elseif (strpos($url, '.jpg') !== false || strpos($url, '.png') !== false || strpos($url, '.gif') !== false) {
						//$instruction->instruction_type = 'remote_image';
						$at = ArtefactType::where('description', 'remote_image')->first();
					} elseif (strpos($url, '.JPG') !== false || strpos($url, '.PNG') !== false || strpos($url, '.GIF') !== false) {
						//$instruction->instruction_type = 'remote_image';
						$at = ArtefactType::where('description', 'remote_image')->first();
					} elseif (strpos($url, '.pdf') !== false || strpos($url, '.PDF') !== false) {
						//$instruction->instruction_type = 'remote_pdf';
						$at = ArtefactType::where('description', 'remote_pdf')->first();
					} else {
						//$instruction->instruction_type = 'remote_document';
						$at = ArtefactType::where('description', 'remote_document')->first();
					}
					if ($at) $at->artefacts()->save($comment);
					break;
				case 'file':
					if (Input::file('answer_upload') && Input::file('answer_upload')->isValid()) {
						$extension = Input::file('answer_upload')->getClientOriginalExtension();
						switch (strtolower($extension)) {
							case 'jpg':
							case 'png':
							case 'gif':
								//$instruction->instruction_type = 'local_image';
								$at = ArtefactType::where('description', 'local_image')->first();
								if ($at) $at->artefacts()->save($comment);
								break;
							case 'pdf':
								//$instruction->instruction_type = 'local_pdf';
								$at = ArtefactType::where('description', 'local_pdf')->first();
								if ($at) $at->artefacts()->save($comment);
								break;
							default:
								//$instruction->instruction_type = 'local_document';
								$at = ArtefactType::where('description', 'local_document')->first();
								if ($at) $at->artefacts()->save($comment);
								break;
						}
						$destinationPath = 'uploads';
						$filename = base64_encode(Input::file('answer_upload')->getClientOriginalName());
						Input::file('answer_upload')->move($destinationPath, $filename);
			
						$comment->url = $filename;
					}
					break;
			}

			if ($comment->url != '' && $comment->type == null) { // Correct toekennen van het comment-type als die nog niet gezet is
				$url = $comment->url;
				if (strpos($url, 'youtube') !== false) { // Youtube video
					if (strpos($url, 'watch?v=')) $comment->url = 'http://www.youtube.com/embed/'.substr($url, strpos($url, 'watch?v=') + 8);
					$comment->artefact_type = 'video_youtube';
				} elseif (strpos($url, 'vimeo') !== false) { // Vimeo video
					$comment->artefact_type = 'video_vimeo';
				} elseif (strpos($url, '.jpg') !== false || strpos($url, '.png') !== false || strpos($url, '.gif') !== false) {
					$comment->artefact_type = 'remote_image';
				} elseif (strpos($url, '.JPG') !== false || strpos($url, '.PNG') !== false || strpos($url, '.GIF') !== false) {
					$comment->artefact_type = 'remote_image';
				} elseif (strpos($url, '.pdf') !== false || strpos($url, '.PDF') !== false) { 
					$comment->artefact_type = 'remote_pdf';
				} else $comment->artefact_type = 'remote_document';
			}
		
			$at = ArtefactType::where('description', $comment->artefact_type)->first();
			if ($at) $at->artefacts()->save($comment); //$comment->type()->save($at);
	
			if ($request->input('answer_parent')) {
				$vader = Artefact::find($request->input('answer_parent'));
				$vader->answers()->save($comment);
	
				$comment->thread = $vader->thread;
			} else {
				$maxthread = Artefact::max('thread');
				$comment->thread = $maxthread+1;
			}
		
			// Attachment verwerken
			if (Input::file('answer_attachment') && Input::file('answer_attachment')->isValid()) {
				$destinationPath = 'uploads/attachments';
				$filename = base64_encode(Input::file('answer_attachment')->getClientOriginalName());
				Input::file('answer_attachment')->move($destinationPath, $filename);
				$comment->attachment = $filename;
			}
		
			if ($comment->save()) {
				
				// Tags verwerken
				// Oude geselecteerde tags komen in answers_tags als array
				if ($request->input('answer_tags')) {
					foreach ($request->input('answer_tags') as $oldtag) {
						$t = Tags::find($oldtag);
						$t->times_used += 1;
						$comment->tags()->save($t);
					}
					// Nieuwe tag
					if ($request->input('answer_new_tag')) {
						$t = new Tags(['tag' => $request->input('answer_new_tag')]);
						$comment->tags()->save($t);
					}
				
				}					
				
				$pater = Artefact::where('thread', $comment->thread)->whereNull('parent_id')->first();
				$pater->last_modified = Carbon::now();
				$pater->last_contributor = $comment->author;
				$pater->save();
				
				
				// Tel hoeveel kinderen er zijn voor de vader
				if ($comment->child_of) {
					$aantalKinderen = Artefact::where('parent_id', $comment->child_of->id)->count();
					return $this->showTopic($comment->child_of->id, $aantalKinderen-1);
				}
				else return $this->showTopic($comment->id, 0);
				
				
			} else {
				
			}
		}
	}
	
	
	public function newInstruction(Request $request) {
		$user = Auth::user();
		if ($user && $user->role=="editor") { // Als de gebruiker ingelogd is en editor is, anders niets doen
			$instruction = new Instruction();
			$instruction->author = $user->id;
			$instruction->active_from = Carbon::now();
			if ($request->input('instruction_title')) $instruction->title = $request->input('instruction_title');
			else $instruction->title = 'No title';

			switch ($request->input('instruction_temp_type')) {
				case 'text':
					if ($request->input('instruction_text')) {
						$instruction->contents = $request->input('instruction_text');
						//$instruction->instruction_type = 'text';
					}
					$at = ArtefactType::where('description', 'text')->first();
					if ($at) $at->instructions()->save($instruction);
					break;
				case 'url':
					if ($request->input('instruction_url')) $instruction->url = $request->input('instruction_url');
					else $instruction->url = 'https://www.youtube.com/embed/YecyKnQUcBY'; // Dummy video
					//$instruction->instruction_type = null;
					$url = $instruction->url;
					if (strpos($url, 'youtube') !== false) { // Youtube video
						if (strpos($url, 'watch?v=')) $instruction->url = 'http://www.youtube.com/embed/'.substr($url, strpos($url, 'watch?v=') + 8);
						//$instruction->instruction_type = 'video_youtube';
						$at = ArtefactType::where('description', 'video_youtube')->first();
					} elseif (strpos($url, 'vimeo') !== false) { // Vimeo video
						//$instruction->instruction_type = 'video_vimeo';
						$at = ArtefactType::where('description', 'video_vimeo')->first();
					} elseif (strpos($url, '.jpg') !== false || strpos($url, '.png') !== false || strpos($url, '.gif') !== false) {
						//$instruction->instruction_type = 'remote_image';
						$at = ArtefactType::where('description', 'remote_image')->first();
					} elseif (strpos($url, '.JPG') !== false || strpos($url, '.PNG') !== false || strpos($url, '.GIF') !== false) {
						//$instruction->instruction_type = 'remote_image';
						$at = ArtefactType::where('description', 'remote_image')->first();
					} elseif (strpos($url, '.pdf') !== false || strpos($url, '.PDF') !== false) {
						//$instruction->instruction_type = 'remote_pdf';
						$at = ArtefactType::where('description', 'remote_pdf')->first();
					} else {
						//$instruction->instruction_type = 'remote_document';
						$at = ArtefactType::where('description', 'remote_document')->first();
					}
					if ($at) $at->instructions()->save($instruction);
					break;
				case 'file':
					if (Input::file('instruction_upload') && Input::file('instruction_upload')->isValid()) {
						$extension = Input::file('instruction_upload')->getClientOriginalExtension();
						switch (strtolower($extension)) {
							case 'jpg':
							case 'png':
							case 'gif':
								//$instruction->instruction_type = 'local_image';
								$at = ArtefactType::where('description', 'local_image')->first();
								if ($at) $at->instructions()->save($instruction);
								break;
							case 'pdf':
								//$instruction->instruction_type = 'local_pdf';
								$at = ArtefactType::where('description', 'local_pdf')->first();
								if ($at) $at->instructions()->save($instruction);
								break;
							default:
								//$instruction->instruction_type = 'local_document';
								$at = ArtefactType::where('description', 'local_document')->first();
								if ($at) $at->instructions()->save($instruction);
								break;
						}
						$destinationPath = 'uploads';
						$filename = base64_encode(Input::file('instruction_upload')->getClientOriginalName());
						Input::file('instruction_upload')->move($destinationPath, $filename);
	
						$instruction->url = $filename;
					}
					break;
			}
			
			if ($request->input('instruction_parent')) $instruction->thread = $request->input('instruction_parent');
			$previous = Instruction::getCurrent($instruction->thread);
			if ($previous) {
				$previous->active_until = $instruction->active_from;
				$previous->save();
			}
				
			if ($instruction->save()) {

				if ($request->input('instruction_types')) {
					foreach ($request->input('instruction_types') as $instructiontype) {
						switch ($instructiontype) {
							case 'text':
								$it = ArtefactType::where('description', 'text')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								break;
							case 'url':
								$it = ArtefactType::where('description', 'video_youtube')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								$it = ArtefactType::where('description', 'video_vimeo')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								$it = ArtefactType::where('description', 'remote_image')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								$it = ArtefactType::where('description', 'remote_pdf')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								$it = ArtefactType::where('description', 'remote_document')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								break;
							case 'file':
								$it = ArtefactType::where('description', 'local_image')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								$it = ArtefactType::where('description', 'local_pdf')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								$it = ArtefactType::where('description', 'local_document')->first();
								if ($it) $instruction->available_types()->attach($it->id);
								break;
						}
						//dd($instructiontype);
						
						//dd($it);
						
					}
				}
				
				return Redirect::back();
			} else {
				return Redirect::back();
			}
		}
	}

	public function newTopic(Request $request) {
		$user = Auth::user();
		if ($user && $user->role=="editor") { // Als de gebruiker ingelogd is en editor is, anders niets doen
			$topic = new Artefact();
			$topic->author = $user->id;
			
			$thread = DB::table('artefacts')->max('thread') + 1;
			$topic->thread = $thread;
			
			if ($request->input('topic_title')) $topic->title = $request->input('topic_title');
			else $topic->title = 'No title';
	
			switch ($request->input('topic_temp_type')) {
				case 'text':
					if ($request->input('topic_text')) {
						$topic->contents = $request->input('topic_text');
					}
					$at = ArtefactType::where('description', 'text')->first();
					if ($at) $at->artefacts()->save($topic);
					break;
				case 'url':
					if ($request->input('topic_url')) $topic->url = $request->input('topic_url');
					else $topic->url = 'https://www.youtube.com/embed/YecyKnQUcBY'; // Dummy video
					$url = $topic->url;
					if (strpos($url, 'youtube') !== false) { // Youtube video
						if (strpos($url, 'watch?v=')) $topic->url = 'http://www.youtube.com/embed/'.substr($url, strpos($url, 'watch?v=') + 8);
						$at = ArtefactType::where('description', 'video_youtube')->first();
					} elseif (strpos($url, 'vimeo') !== false) { // Vimeo video
						$at = ArtefactType::where('description', 'video_vimeo')->first();
					} elseif (strpos($url, '.jpg') !== false || strpos($url, '.png') !== false || strpos($url, '.gif') !== false) {
						$at = ArtefactType::where('description', 'remote_image')->first();
					} elseif (strpos($url, '.JPG') !== false || strpos($url, '.PNG') !== false || strpos($url, '.GIF') !== false) {
						$at = ArtefactType::where('description', 'remote_image')->first();
					} elseif (strpos($url, '.pdf') !== false || strpos($url, '.PDF') !== false) {
						$at = ArtefactType::where('description', 'remote_pdf')->first();
					} else {
						$at = ArtefactType::where('description', 'remote_document')->first();
					}
					if ($at) $at->artefacts()->save($topic);
					break;
				case 'file':
					if (Input::file('topic_upload') && Input::file('topic_upload')->isValid()) {
						$extension = Input::file('topic_upload')->getClientOriginalExtension();
						switch (strtolower($extension)) {
							case 'jpg':
							case 'png':
							case 'gif':
								$at = ArtefactType::where('description', 'local_image')->first();
								if ($at) $at->artefacts()->save($topic);
								break;
							case 'pdf':
								$at = ArtefactType::where('description', 'local_pdf')->first();
								if ($at) $at->artefacts()->save($topic);
								break;
							default:
								$at = ArtefactType::where('description', 'local_document')->first();
								if ($at) $at->artefacts()->save($topic);
								break;
						}
						$destinationPath = 'uploads';
						$filename = base64_encode(Input::file('topic_upload')->getClientOriginalName());
						Input::file('topic_upload')->move($destinationPath, $filename);
	
						$topic->url = $filename;
					}
					break;
			}
		
			if ($topic->save()) {
				if ($request->input('topic_new_tag')) {
					foreach ($request->input('topic_new_tag') as $newtag) {
						$existingtag = Tags::where('tag', $newtag)->first();
						if ($existingtag) {
							$existingtag->artefacts()->save($topic);
						} else {
							$newTag = Tags::create(['tag'=>$newtag, 'times_used'=>0]);
							$newTag->artefacts()->save($topic);
						}
					}
				}
				
				
				
				return Redirect::back();
			} else {
				return Redirect::back();
			}
		}
	}

}
