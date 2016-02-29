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
use Exception;
use Mail;
use Response;
use File;
use URL;

class BmoocController extends Controller {

    public function __construct() {
        //$this->middleware('auth', ['except' => 'index']);
    }

    public function getLogout() {
        Auth::logout();
        return Redirect::to('/');
    }

    public function index(Request $request) {
        //$user = Auth::user();
        $user = $request->user();
        //dd($request);
        $topics = Artefact::with(['active_instruction', 'the_author', 'tags', 'last_modifier'])->whereNull('parent_id')->orderBy('created_at', 'desc')->orderBy('last_modified', 'desc')->get();
        $auteurs = DB::table('users')->select('id', 'name')->distinct()->get();
        $tags = Tags::orderBy('tag')->get();

        $aantalAntwoorden = DB::table('artefacts')->select(DB::raw('count(*) as aantal_antwoorden, thread'))
                        ->groupBy('thread')->get();
        return view('index', ['topic' => $topics, 'user' => $user, 'auteurs' => $auteurs, 'tags' => $tags, 'aantalAntwoorden' => $aantalAntwoorden]);
    }

    public function feedback(){
        $data = Input::all();

        if($data['email'] == "") $data['email'] = "teis.degreve@luca-arts.be";
        if($data['name'] == "") $data['name'] = "Teis De Greve";

        $validator = Validator::make($data,
            array(
              'name' => 'required',
              'email' => 'required|email',
              'body' => 'required',
            )
        );

          if ($validator->fails())
          {
              print("Oops. Something went wrong. Please try again or send your feedback to <a href=\"mailto:teis.degreve@luca-arts.be\">teis.degreve@luca-arts.be</a>");
          } else {

            Mail::send('email.feedback', $data, function($m) use ($data) {
                $m->from($data['email'], $data['name'])
                    ->to('teis.degreve@luca-arts.be')
                    ->subject('bMOOC Feedback');
            });

            print("Thank you for your feedback!");
          }
    }

    public function showTopic($links, $answer = null) {
        $user = Auth::user();
        $artefactLinks = Artefact::find($links);
        if ($artefactLinks) {
            return view('topic', ['artefactLeft' => $links, 'answerRight' => $answer, 'user' => $user]);
        } else {
            return view('no_topic');
        }
    }

    public function searchDiscussions($author = null, $tag = null, $keyword = null) {
        $user = Auth::user();
        //filter the artefacts on author first
        $discussies = DB::table('artefacts');
        if (isset($author) && $author != "all") {
            $discussies
                    ->where('author', $author);
        }
        // tags
        if (isset($tag) && $tag != "all") {
            $discussies
                    ->join('artefacts_tags', 'artefacts.id', '=', 'artefacts_tags.artefact_id')
                    ->join('tags', 'artefacts_tags.tag_id', '=', 'tags.id')
                    ->where('tag_id', $tag);
        }
        // query
        if (isset($keyword)) {
            $discussies
                    ->where(function($q) use( &$keyword) {
                        $q
                        ->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('contents', 'LIKE', '%' . $keyword . '%');
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
                ->orderBy('last_modified', 'desc')
                ->get();
        // extra information needed
        $auteurs = DB::table('users')->select('name', 'id')->distinct()->get();
        $tags = Tags::orderBy('tag')->get();
        $aantalAntwoorden = DB::table('artefacts')
                        ->select(DB::raw('count(*) as aantal_antwoorden, thread'))
                        ->groupBy('thread')->get();

        return view('index', ['topic' => $discs, 'user' => $user, 'auteurs' => $auteurs, 'tags' => $tags, 'titel' => "met tag '" . $tag . "'", 'aantalAntwoorden' => $aantalAntwoorden, 'search' => ['tag' => $tag, 'author' => $author, 'keyword' => $keyword]]);
    }

    public function commentDiscussion(Request $request) {
        $user = Auth::user();
        if ($user) { // Als de gebruiker ingelogd is, anders niets doen
            $filename = uniqid();
            try {
                DB::beginTransaction();
                $comment = new Artefact();
                $comment->author = $user->id;

                // Titel van het artefact zetten
                if ($request->input('answer_title')) $comment->title = $request->input('answer_title');
                else $comment->title = 'No title';

                if ($request->input('answer_copyright')) $comment->copyright = $request->input('answer_copyright');

                // De eigenlijke inhoud verwerken en het type bepalen en juist zetten
                $at = null;
                switch ($request->input('answer_temp_type')) {
                    case 'text':
                        if ($request->input('answer_text')) {
                            $comment->contents = $request->input('answer_text');
                        }
                        $at = ArtefactType::where('description', 'text')->first();
                        break;
                    case 'video':
                        if ($request->input('answer_url') && $request->input('answer_url')!=null && $request->input('answer_url')!='') { // URL meegegeven voor video
                            $url = $request->input('answer_url');
                            if (strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false) { // Youtube video
                                if (strpos($url, 'watch?v='))
                                    $comment->url = 'http://www.youtube.com/embed/' . substr($url, strpos($url, 'watch?v=') + 8);
                                elseif (strpos($url, 'youtub.be/'))
                                    $comment->url = 'http://www.youtube.com/embed/' . substr($url, strpos($url, 'youtu.be/') + 9);
                                $at = ArtefactType::where('description', 'video_youtube')->first();
                            } elseif (strpos($url, 'vimeo.com') !== false) { // Vimeo video
                                $comment->url = '//player.vimeo.com/video/'.substr($url, strpos($url, 'vimeo.com/') + 10);
                                $at = ArtefactType::where('description', 'video_vimeo')->first();
                            } else {
                                throw new Exception('The URL you entered is not a valid link to a YouTube or Vimeo video.');
                            }
                        } else { // Kan niet voorkomen, maar voor de veiligheid wel fout opwerpen
                            //$topic->url = 'https://www.youtube.com/embed/YecyKnQUcBY'; // Dummy video
                            throw new Exception('No video URL provided for new contribution of type video');
                        }
                        break;
                    case 'image':
                        if (Input::file('answer_upload') && Input::file('answer_upload')->isValid()) {
                            $extension = strtolower(Input::file('answer_upload')->getClientOriginalExtension());
                            if (in_array($extension, ['jpg', 'png', 'gif', 'jpeg'])) {
                                $destinationPath = 'uploads';
                                Input::file('answer_upload')->move($destinationPath, $filename);
                                $comment->url = $filename;
                                $at = ArtefactType::where('description', 'local_image')->first();
                            } else throw new Exception('Image should be a JPEG, PNG or GIF.');
                        } elseif ($request->input('answer_url') && $request->input('answer_url')!=null && $request->input('answer_url')!='') { // URL voor de afbeelding
                            if (getimagesize($request->input('answer_url'))) { // De afbeelding is een echte afbeelding als dit niet false teruggeeft
                                $comment->url = $request->input('answer_url');
                                $at = ArtefactType::where('description', 'remote_image')->first();
                            }
                        }
                        break;
                    case 'file':
                        if (Input::file('answer_upload') && Input::file('answer_upload')->isValid()) {
                            $extension = strtolower(Input::file('answer_upload')->getClientOriginalExtension());
                            if (in_array($extension, ['pdf'])) {
                                $destinationPath = 'uploads';
                                Input::file('answer_upload')->move($destinationPath, $filename);
                                $comment->url = $filename;
                                $at = ArtefactType::where('description', 'local_pdf')->first();
                            } else throw new Exception('File should be a PDF.');
                        } elseif ($request->input('answer_url') && $request->input('answer_url')!=null && $request->input('answer_url')!='') { // URL voor de afbeelding
                            if (getimagesize($request->input('answer_url'))) { // De afbeelding is een echte afbeelding als dit niet false teruggeeft
                                $comment->url = $request->input('answer_url');
                                $at = ArtefactType::where('description', 'remote_pdf')->first();
                            } else throw new Exception('The document in the url is not an image');
                        }
                        break;
                }

                // Thumbnails opslaan
                // small
                if($request->input('thumbnail_small') && $request->input('thumbnail_small') != null && $request->input('thumbnail_small') != ''){
                    $destinationPath = 'uploads/thumbnails/small/' . $comment->url;
                    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('thumbnail_small')));
                    file_put_contents($destinationPath, $data);
                }
                // large
                if($request->input('thumbnail_large') && $request->input('thumbnail_large') != null && $request->input('thumbnail_large') != ''){
                    $destinationPath = 'uploads/thumbnails/large/' . $comment->url;
                    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('thumbnail_large')));
                    file_put_contents($destinationPath, $data);
                }

                if ($at) $at->artefacts()->save($comment);
                else throw new Exception('Selected file is not a valid image or PDF.');
                // Einde inhoud verwerken en type bepalen

                if ($request->input('answer_parent')) {
                    $vader = Artefact::find($request->input('answer_parent'));
                    $vader->children()->save($comment);

                    $comment->thread = $vader->thread;
                } else {
                    $maxthread = Artefact::max('thread');
                    $comment->thread = $maxthread + 1;
                }

                // Attachment verwerken
                if (Input::file('answer_attachment') && Input::file('answer_attachment')->isValid()) {
                    $extension = strtolower(Input::file('answer_attachment')->getClientOriginalExtension());
                    if (in_array($extension, ['jpg', 'png', 'gif', 'jpeg', 'pdf'])) {
                        $destinationPath = 'uploads/attachments';
                        $filename = base64_encode(Input::file('answer_attachment')->getClientOriginalName() . time()).'.'.$extension;
                        Input::file('answer_attachment')->move($destinationPath, $filename);
                        $comment->attachment = $filename;
                    } else throw new Exception('Attachment should be a JPG, PNG, GIF or PDF');
                }

                $comment->save();

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
                        $t = new Tags(['tag' => $request->input('answer_new_tag'), 'times_used' => 1]);
                        $comment->tags()->save($t);
                    }
                }

                $pater = Artefact::where('thread', $comment->thread)->whereNull('parent_id')->first();
                $pater->last_modified = Carbon::now();
                $pater->last_contributor = $comment->author;
                $pater->save();

                DB::commit();

                // Tel hoeveel kinderen er zijn voor de vader
                if ($comment->child_of) {
                    $aantalKinderen = Artefact::where('parent_id', $comment->child_of->id)->count();
                    $url = 'topic/' . $comment->child_of->id . '/' . ($aantalKinderen - 1);
                    if ( $request->isXmlHttpRequest() ) {
                        return Response::json( [
                            'status' => '200',
                            'url' => URL::to($url)
                        ], 200);
                    } else
                    return $this->showTopic($comment->child_of->id, $aantalKinderen - 1);
                } else
                    if ( $request->isXmlHttpRequest() ) {
                        return Response::json( [
                            'status' => '200',
                            'url' => URL::to('topic/' . $comment->child_of->id)
                        ], 200);
                    } else
                        return $this->showTopic($comment->id, 0);
                
            } catch (Exception $e) {
                DB::rollback();
                //return view('errors.topic', ['error' => $e]);
                throw $e;
            }

        }
    }

    public function newInstruction(Request $request) {
        $user = Auth::user();
        if ($user && $user->role == "editor") { // Als de gebruiker ingelogd is en editor is, anders niets doen
            $filename = uniqid();
            try {
                DB::beginTransaction();
                $instruction = new Instruction();
                $instruction->author = $user->id;
                $instruction->active_from = Carbon::now();
                if ($request->input('instruction_title'))
                    $instruction->title = $request->input('instruction_title');
                else
                    $instruction->title = 'No title';

                $at = null;
                switch ($request->input('instruction_temp_type')) {
                    case 'text':
                        if ($request->input('instruction_text')) {
                            $instruction->contents = $request->input('instruction_text');
                        }
                        $at = ArtefactType::where('description', 'text')->first();
                        break;
                    case 'video':
                        if ($request->input('instruction_url') && $request->input('instruction_url') != null && $request->input('instruction_url') != '') { // URL meegegeven voor video
                            $url = $request->input('instruction_url');
                            if (strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false) { // Youtube video
                                if (strpos($url, 'watch?v='))
                                    $instruction->url = 'http://www.youtube.com/embed/' . substr($url, strpos($url, 'watch?v=') + 8);
                                elseif (strpos($url, 'youtub.be/'))
                                    $instruction->url = 'http://www.youtube.com/embed/' . substr($url, strpos($url, 'youtu.be/') + 9);
                                $at = ArtefactType::where('description', 'video_youtube')->first();
                            } elseif (strpos($url, 'vimeo.com') !== false) { // Vimeo video
                                $instruction->url = '//player.vimeo.com/video/' . substr($url, strpos($url, 'vimeo.com/') + 10);
                                $at = ArtefactType::where('description', 'video_vimeo')->first();
                            } else {
                                throw new Exception('The URL you entered is not a valid link to a YouTube or Vimeo video.');
                            }
                        } else { // Kan niet voorkomen, maar voor de veiligheid wel fout opwerpen
                            //$topic->url = 'https://www.youtube.com/embed/YecyKnQUcBY'; // Dummy video
                            throw new Exception('No video URL provided for new instruction of type video');
                        }
                        break;
                    case 'image':
                        if (Input::file('instruction_upload') && Input::file('instruction_upload')->isValid()) {
                            $extension = strtolower(Input::file('instruction_upload')->getClientOriginalExtension());
                            if (in_array($extension, ['jpg', 'png', 'gif', 'jpeg'])) {
                                $destinationPath = 'uploads';
                                Input::file('instruction_upload')->move($destinationPath, $filename);
                                $instruction->url = $filename;
                                $at = ArtefactType::where('description', 'local_image')->first();
                            } else
                                throw new Exception('Wrong file uploaded for new instruction');
                        } elseif ($request->input('instruction_url') && $request->input('instruction_url') != null && $request->input('instruction_url') != '') { // URL voor de afbeelding
                            if (getimagesize($request->input('instruction_url'))) { // De afbeelding is een echte afbeelding als dit niet false teruggeeft
                                $instruction->url = $request->input('instruction_url');
                                $at = ArtefactType::where('description', 'remote_image')->first();
                            } else throw new Exception('The document in the url is not an image');
                        }
                        break;
                    case 'file':
                        if (Input::file('instruction_upload') && Input::file('instruction_upload')->isValid()) {
                            $extension = strtolower(Input::file('instruction_upload')->getClientOriginalExtension());
                            if (in_array($extension, ['pdf'])) {
                                $destinationPath = 'uploads';
                                Input::file('instruction_upload')->move($destinationPath, $filename);
                                $instruction->url = $filename;
                                $at = ArtefactType::where('description', 'local_pdf')->first();
                            } else
                                throw new Exception('Wrong file uploaded for new topic');
                        } elseif ($request->input('instruction_url') && $request->input('instruction_url') != null && $request->input('instruction_url') != '') { // URL voor de afbeelding
                            if (getimagesize($request->input('instruction_url'))) { // De afbeelding is een echte afbeelding als dit niet false teruggeeft
                                $instruction->url = $request->input('instruction_url');
                                $at = ArtefactType::where('description', 'remote_pdf')->first();
                            }
                        }
                        break;
                }

                // Thumbnails opslaan
                // small
                if($request->input('thumbnail_small') && $request->input('thumbnail_small') != null && $request->input('thumbnail_small') != ''){
                    $destinationPath = 'uploads/thumbnails/small/' . $instruction->url;
                    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('thumbnail_small')));
                    file_put_contents($destinationPath, $data);
                }
                // large
                if($request->input('thumbnail_large') && $request->input('thumbnail_large') != null && $request->input('thumbnail_large') != ''){
                    $destinationPath = 'uploads/thumbnails/large/' . $instruction->url;
                    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('thumbnail_large')));
                    file_put_contents($destinationPath, $data);
                }

                if ($at)
                    $at->instructions()->save($instruction);
                else
                    throw new Exception('Error creating new instruction (wrong type provided)');
                // Einde inhoud verwerken en type bepalen

                // Set the thread of the instruction
                if ($request->input('instruction_parent')) $instruction->thread = $request->input('instruction_parent');
                
                // Disable the previous instruction for the thread
                $previous = Instruction::getCurrent($instruction->thread);
                if ($previous) {
                    $previous->active_until = $instruction->active_from;
                    $previous->save();
                }

                $instruction->save();

                // Set the available artefact types for the thread
                if ($request->input('instruction_types')) {
                    foreach ($request->input('instruction_types') as $instructiontype) {
                        switch ($instructiontype) {
                            case 'text':
                                $it = ArtefactType::where('description', 'text')->first();
                                if ($it)
                                    $instruction->available_types()->attach($it->id);
                                break;
                            case 'image':
                                $it = ArtefactType::where('description', 'local_image')->first();
                                if ($it)
                                    $instruction->available_types()->attach($it->id);
                                $it = ArtefactType::where('description', 'remote_image')->first();
                                if ($it)
                                    $instruction->available_types()->attach($it->id);
                                break;
                            case 'video':
                                $it = ArtefactType::where('description', 'video_youtube')->first();
                                if ($it)
                                    $instruction->available_types()->attach($it->id);
                                $it = ArtefactType::where('description', 'video_vimeo')->first();
                                if ($it)
                                    $instruction->available_types()->attach($it->id);
                                break;
                            case 'file':
                                $it = ArtefactType::where('description', 'local_pdf')->first();
                                if ($it)
                                    $instruction->available_types()->attach($it->id);
                                $it = ArtefactType::where('description', 'remote_pdf')->first();
                                if ($it)
                                    $instruction->available_types()->attach($it->id);
                                break;
                        }
                    }
                }
                // endif set the available artefact types for the thread
                
                DB::commit();
                // add handler for Ajax requests
                if ( $request->isXmlHttpRequest() ) {
                    return Response::json( [
                        'status' => '200',
                        'refresh' => 'refresh',
                        'url' => URL::to('/')
                    ], 200);
                } else {
                    return Redirect::back();
                }

            } catch (Exception $e) {
                DB::rollback();
                //return view('errors.topic', ['error' => $e]);
                throw $e;
            }

        } // End if ($user)
    }

    public function newTopic(Request $request) {
        $user = Auth::user();
        if ($user && $user->role == "editor") { // Als de gebruiker ingelogd is en editor is, anders niets doen
        try {
            $filename = uniqid();
            DB::beginTransaction();
            $topic = new Artefact();
            $topic->author = $user->id;

            $thread = DB::table('artefacts')->max('thread') + 1;
            $topic->thread = $thread;

            if ($request->input('topic_title')) $topic->title = $request->input('topic_title');
            else $topic->title = 'No title';

            if ($request->input('topic_copyright')) $topic->copyright = $request->input('topic_copyright');

            // De eigenlijke inhoud verwerken en het type bepalen en juist zetten
            $at = null;
            switch ($request->input('topic_temp_type')) {
                case 'text':
                    if ($request->input('topic_text')) {
                        $topic->contents = $request->input('topic_text');
                    }
                    $at = ArtefactType::where('description', 'text')->first();
                    break;
                case 'video':
                    if ($request->input('topic_url') && $request->input('topic_url')!=null && $request->input('topic_url')!='') { // URL meegegeven voor video
                        $url = $request->input('topic_url');
                        if (strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false) { // Youtube video
                            if (strpos($url, 'watch?v='))
                                $topic->url = 'http://www.youtube.com/embed/' . substr($url, strpos($url, 'watch?v=') + 8);
                            elseif (strpos($url, 'youtub.be/'))
                                $topic->url = 'http://www.youtube.com/embed/' . substr($url, strpos($url, 'youtu.be/') + 9);
                            $at = ArtefactType::where('description', 'video_youtube')->first();
                        } elseif (strpos($url, 'vimeo.com') !== false) { // Vimeo video
                            $topic->url = '//player.vimeo.com/video/'.substr($url, strpos($url, 'vimeo.com/') + 10);
                            $at = ArtefactType::where('description', 'video_vimeo')->first();
                        } else {
                            throw new Exception('The URL you entered is not a valid link to a YouTube or Vimeo video.');
                        }
                    } else { // Kan niet voorkomen, maar voor de veiligheid wel fout opwerpen
                        //$topic->url = 'https://www.youtube.com/embed/YecyKnQUcBY'; // Dummy video
                        throw new Exception('No video URL provided for new topic of type video');
                    }
                    break;
                case 'image':
                    if (Input::file('topic_upload') && Input::file('topic_upload')->isValid()) {
                        $extension = strtolower(Input::file('topic_upload')->getClientOriginalExtension());
                        if (in_array($extension, ['jpg', 'png', 'gif', 'jpeg'])) {
                            $destinationPath = 'uploads';
                            Input::file('topic_upload')->move($destinationPath, $filename);
                            $topic->url = $filename;
                            $at = ArtefactType::where('description', 'local_image')->first();
                        } else throw new Exception('Image should be a JPEG, PNG or GIF.');
                    } elseif ($request->input('topic_url') && $request->input('topic_url')!=null && $request->input('topic_url')!='') { // URL voor de afbeelding
                        if (getimagesize($request->input('topic_url'))) { // De afbeelding is een echte afbeelding als dit niet false teruggeeft
                            $topic->url = $request->input('topic_url');
                            $at = ArtefactType::where('description', 'remote_image')->first();
                        } else throw new Exception('The document in the url is not an image');
                    }
                    break;
                case 'file':
                    if (Input::file('topic_upload') && Input::file('topic_upload')->isValid()) {
                        $extension = strtolower(Input::file('topic_upload')->getClientOriginalExtension());
                        if (in_array($extension, ['pdf'])) {
                            $destinationPath = 'uploads';
                            Input::file('topic_upload')->move($destinationPath, $filename);
                            $topic->url = $filename;
                            $at = ArtefactType::where('description', 'local_pdf')->first();
                        } else throw new Exception('File should be a PDF.');
                    } elseif ($request->input('topic_url') && $request->input('topic_url')!=null && $request->input('topic_url')!='') { // URL voor de afbeelding
                        if (getimagesize($request->input('topic_url'))) { // De afbeelding is een echte afbeelding als dit niet false teruggeeft
                            $topic->url = $request->input('topic_url');
                            $at = ArtefactType::where('description', 'remote_pdf')->first();
                        }
                    }
                    break;
            }

            // Thumbnails opslaan
            // small
            if($request->input('thumbnail_small') && $request->input('thumbnail_small') != null && $request->input('thumbnail_small') != ''){
                $destinationPath = 'uploads/thumbnails/small/' . $topic->url;
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('thumbnail_small')));
                file_put_contents($destinationPath, $data);
            }
            // large
            if($request->input('thumbnail_large') && $request->input('thumbnail_large') != null && $request->input('thumbnail_large') != ''){
                $destinationPath = 'uploads/thumbnails/large/' . $topic->url;
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('thumbnail_large')));
                file_put_contents($destinationPath, $data);
            }


            if ($at) $at->artefacts()->save($topic);
            else throw new Exception('Selected file is not a valid image or PDF.');
            // Einde inhoud verwerken en type bepalen
            
            // Bijlage verwerken
            if (Input::file('topic_attachment') && Input::file('topic_attachment')->isValid()) {
                $extension = strtolower(Input::file('topic_attachment')->getClientOriginalExtension());
                if (in_array($extension, ['jpg', 'png', 'gif', 'jpeg', 'pdf'])) {
                    $destinationPath = 'uploads/attachments';
                    $filename = base64_encode(Input::file('topic_attachment')->getClientOriginalName() . time()).'.'.$extension;
                    Input::file('topic_attachment')->move($destinationPath, $filename);
                    //$topic->url = $filename;
                    $topic->attachment = $filename;
                } else throw new Exception('Attachment should be a JPG, PNG, GIF or PDF');
            }
            
            // Topic opslaan
            $topic->save();
            
            // Tags verwerken
            if ($request->input('topic_new_tag')) {
                foreach ($request->input('topic_new_tag') as $newtag) {
                    if ($newtag != '') {
                        $existingtag = Tags::where('tag', strtolower($newtag))->first();
                        if ($existingtag) {
                            $existingtag->artefacts()->save($topic);
                        } else {
                            $newTag = Tags::create(['tag' => strtolower($newtag), 'times_used' => 1]);
                            $newTag->artefacts()->save($topic);
                        }
                    } else throw new Exception('Tags must not be empty!');
                }
            }

            DB::commit();
            // add handler for Ajax requests
            if ( $request->isXmlHttpRequest() ) {
                return Response::json( [
                    'status' => '200',
                    'url' => URL::to('/')
                ], 200);
            } else {
                return Redirect::back();
            }

        } catch (Exception $e) {
            DB::rollback();
            //return view('errors.topic', ['error' => $e]);
            throw $e;
        }
        } // End if ($user)
    }

    public function datavis(Request $request) {
		//$user = Auth::user();
		$user = $request->user();
		//dd($request);
		$topics = Artefact::with(['the_author', 'tags', 'last_modifier'])->whereNull('parent_id')->orderBy('created_at', 'desc')->orderBy('last_modified', 'desc')->get();
		$auteurs = DB::table('users')->select('id', 'name')->distinct()->get();
		$tags = Tags::orderBy('tag')->get();

		$aantalAntwoorden = DB::table('artefacts')->select(DB::raw('count(*) as aantal_antwoorden, thread'))
                     ->groupBy('thread')->get();
		return view('datavis', ['topic'=>$topics, 'user'=>$user, 'auteurs' => $auteurs, 'tags' => $tags, 'aantalAntwoorden'=>$aantalAntwoorden]);
	}

    public function getImage($id){
        $a = Artefact::find($id);
        $path = base_path().'/../uploads/thumbnails/large/'.$a->url;
        if (file_exists($path)) {
            $filetype = mime_content_type( $path );
            $response = Response::make( File::get( $path ) , 200 );
            $response->header('Content-Type', $filetype);
            return $response;
        }
        return BmoocController::getImageOriginal($id);
    }

    public function getImageThumbnail($id){
        // get url from id
        $a = Artefact::find($id);
        $path = base_path().'/../uploads/thumbnails/small/'.$a->url;
        // check if the artefact has a thumbnail based on id
        if (file_exists($path)) {
            $filetype = mime_content_type( $path );
            $response = Response::make( File::get( $path ) , 200 );
            $response->header('Content-Type', $filetype);
            return $response;
        }
        return BmoocController::getImage($id);

    }

    public function getImageOriginal($id){
        $a = Artefact::find($id);
        $path = base_path().'/../uploads/'.$a->url;
        if (file_exists($path)) {
            $filetype = mime_content_type( $path );
            $response = Response::make( File::get( $path ) , 200 );
            $response->header('Content-Type', $filetype);
            return $response;
        } else if($a->artefact_type == 31){
            $url = str_replace('www.youtube.com/embed', 'img.youtube.com/vi', $a->url);
            $url .= '/0.jpg';
            $response = Response::make( file_get_contents($url), 200 );
            $response->header('Content-Type', 'image/jpeg');
            return $response;
        } else if($a->artefact_type == 32){
            $oembed_endpoint = 'http://vimeo.com/api/oembed';
            $url = $oembed_endpoint . '.json?url=' . rawurlencode($a->url);
            $json = file_get_contents($url);
            $obj = json_decode($json);
            $response = Response::make( file_get_contents($obj->thumbnail_url), 200 );
            $response->header('Content-Type', 'image/jpeg');
            return $response;
        }
        abort(404, 'Image not found');
    }
}
