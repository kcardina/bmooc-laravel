<?php

namespace App\Http\Controllers;

use Auth;
use Request;
use App;
use Exception;
use Input;
use Artefacts;
use App\User;
use App\Artefact;
use App\Tags;
use stdClass;
use DB;

class AdminController extends Controller {

    public function __construct() {
        //$this->middleware('auth', ['except' => 'index']);
    }

    public function basic(Request $request) {
        // LIST OF TOPICS
        $topics = DB::table('artefacts')
            ->orderBy('updated_at', 'desc')
            ->whereNull('parent_id')
            ->get();
        $topic = Input::get('topic');
        if(!is_numeric($topic)) $topic = null;

        $artefacts = new stdClass();
        $users = new stdClass();
        $tags = new stdClass();
        // ALGEMEEN
        $artefacts->count = DB::table('artefacts')
            ->where('thread', 'LIKE', $topic)
            ->count();
        if($topic)
            $users->count = sizeof(DB::table('users')
                ->select('users.id')
                ->leftJoin('artefacts', 'users.id', '=', 'artefacts.author')
                ->where('thread', 'LIKE', $topic)
                ->groupBy('users.id')
                ->get());
        else $users->count = DB::table('users')->count();
        $users->all = DB::table('users')->count();
        if($topic)
            $tags->count = sizeof(DB::table('artefacts_tags')
                ->join('artefacts', 'artefact_id', '=', 'artefacts.id')
                ->where('thread', 'LIKE', $topic)
                ->groupBy('tag_id')
                ->get());
        else $tags->count = DB::table('tags')->count();
        // ARTEFACTS
        $artefacts->types = [
            "text" => DB::table('artefacts')
                ->where('thread', 'LIKE', $topic)
                ->where('artefact_type', '28')
                ->count(),
            "image" => DB::table('artefacts')
                ->where('thread', 'LIKE', $topic)
                ->where(function ($query) {
                    $query->where('artefact_type', '29')
                        ->orwhere('artefact_type', '30');
                })
                ->count(),
            "video" => DB::table('artefacts')
                ->where('thread', 'LIKE', $topic)
                ->where(function ($query) {
                    $query->where('artefact_type', '31')
                        ->orwhere('artefact_type', '32');
                })
                ->count(),
            "pdf" => DB::table('artefacts')
                ->where('thread', 'LIKE', $topic)
                ->where(function ($query) {
                    $query->where('artefact_type', '33')
                        ->orwhere('artefact_type', '34');
                })
                ->count()
        ];
        // USERS
        $users->topten = DB::table('artefacts')
            ->select('users.name', DB::raw('COUNT(author) AS post_count'))
            ->join('users', 'artefacts.author', '=', 'users.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('artefacts.author')
            ->orderBy('post_count', 'DESC')
            ->limit(10)
            ->get();
        $users->active = sizeof(DB::table('artefacts')
            ->join('users', 'artefacts.author', '=', 'users.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('artefacts.author')
            ->get());
        $users->passive = $users->all - $users->active;
        $users->users = DB::table('artefacts')
            ->select('users.name', DB::raw('COUNT(author) AS post_count'))
            ->join('users', 'artefacts.author', '=', 'users.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('artefacts.author')
            ->orderBy('post_count', 'DESC')
            ->get();
        // TAGS
        $tags->topten = DB::table('artefacts_tags')
            ->select('tag_id', 'tag', DB::raw('count(*) as times_used'))
            ->leftJoin('artefacts', 'artefact_id', '=', 'artefacts.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('tag')
            ->orderBy('times_used', 'DESC')
            ->orderBy('tag', 'ASC')
            ->limit(10)
            ->join('tags', 'tag_id', '=', 'tags.id')
            ->get();
        $tags->single = DB::table('artefacts_tags')
            ->select('tag_id', 'tag', DB::raw('count(*) as times_used'))
            ->leftJoin('artefacts', 'artefact_id', '=', 'artefacts.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('tag')
            ->orderBy('tag', 'ASC')
            ->having('times_used', '=', 1)
            ->join('tags', 'tag_id', '=', 'tags.id')
            ->get();
        /*$temp = DB::table('artefacts')
            ->select(DB::raw('artefacts.thread, artefacts_tags.tag_id, COUNT(*) AS ct'))
            ->distinct()
            ->join('artefacts_tags', 'artefacts.id', '=', 'artefacts_tags.artefact_id')
            ->groupBy('artefacts_tags.tag_id')
            ->having('ct', '>', 1)
            ->orderBY('ct', 'DESC')
            ->get();
        $sub = DB::table('artefacts_tags')
            ->select(DB::raw('tag_id, tag, thread'))
            ->groupBy('tag')
            ->join('tags', 'tag_id', '=', 'id')
            ->join('artefacts', 'artefacts_tags.artefact_id', '=', 'artefacts.id')
            ->distinct()
            ->get();
        //$tags->multiple = DB::table('tags'); */
        $user = Auth::user();
        if ($user && $user->role == "editor") {
            return view('admin.data.basic', ['topics' => $topics, 'topic' => $topic, 'artefacts' => $artefacts, 'users' => $users, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }
    }


    /*
    public function index(Request $request) {
        $artefacts = new stdClass();
        $users = new stdClass();
        $tags = new stdClass();
        // ALGEMEEN
        $artefacts->aantal = DB::table('artefacts')->count();
        $users->aantal = DB::table('users')->count();
        $tags->aantal = DB::table('tags')->count();
        // ARTEFACTS
        $artefacts->types = [
            "text" => DB::table('artefacts')->where('artefact_type', '28')->count(),
            "image" => DB::table('artefacts')->where('artefact_type', '29')->orwhere('artefact_type', '30')->count(),
            "video" => DB::table('artefacts')->where('artefact_type', '31')->orwhere('artefact_type', '32')->count(),
            "pdf" => DB::table('artefacts')->where('artefact_type', '33')->orwhere('artefact_type', '34')->count()
        ];
        $artefacts->progress = json_encode(DB::table('artefacts')
            ->select(DB::raw('artefacts.updated_at, CAST(artefacts.updated_at AS DATE) date, COUNT(updated_at) AS amount'))
            ->groupBy(DB::raw('CAST(updated_at AS DATE)'))
            ->get());
        // USERS
        $users->topten = DB::table('artefacts')
            ->select(DB::raw('users.name, COUNT(author) AS theCount'))
            ->join('users', 'artefacts.author', '=', 'users.id')
            ->groupBy('artefacts.author')
            ->orderBy('theCount', 'DESC')
            ->limit(10)
            ->get();
        $users->passive = DB::table('users')
            ->select('name')
            ->leftJoin('artefacts', 'users.id', '=', 'artefacts.author')
            ->where('artefacts.author', '=', NULL)
            ->count();
        // TAGS
        // TAGS
        $tags->topten = DB::table('tags')
            ->select('tag', 'times_used')
            ->orderBy('times_used', 'DESC')
            ->orderBy('tag', 'ASC')
            ->limit(10)
            ->get();
        $user = Auth::user();
        if ($user && $user->role == "editor") {
            return view('admin.data.simple', ['artefacts' => $artefacts, 'users' => $users, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }
    }*/

    public function progress(Request $request){
        // LIST OF TOPICS
        $topics = DB::table('artefacts')
            ->orderBy('updated_at', 'desc')
            ->whereNull('parent_id')
            ->get();
        $topic = Input::get('topic');
        if(!is_numeric($topic)) $topic = null;

        $artefacts = DB::table('artefacts')
            ->select('id', 'created_at')
            ->where('thread', 'LIKE', $topic)
            ->get();

        $user = Auth::user();
        if ($user && $user->role == "editor") {
            return view('admin.data.progress', ['topics' => $topics, 'topic' => $topic, 'artefacts' => $artefacts]);
        } else {
            App::abort(401, 'Not authenticated');
        }
    }

    public function tree(Request $request){
        // LIST OF TOPICS
        $topics = DB::table('artefacts')
            ->orderBy('updated_at', 'desc')
            ->whereNull('parent_id')
            ->get();
        $topic = Input::get('topic');
        if($topic == "all") $topic = null;
        else if(!is_numeric($topic)) $topic = $topics[0]->thread;

        // BUILD TREE
        $parent = DB::table('artefacts')
            ->select('id')
            ->where('thread', 'LIKE', $topic)
            ->where('parent_id', '=', NULL)
            ->get();
        $parent = Artefact::all()->find($parent[0]->id);

        $tree = $parent;

        $parent->children = BmoocJsonController::buildTree($parent->children, $parent->id);

        // GET PLAIN LIST
        $list = DB::table('artefacts')
            ->where('thread', 'LIKE', $topic)
            ->get();

        $tags = DB::table('artefacts_tags')
            ->select('artefact_id', 'tag_id')
            ->leftJoin('artefacts', 'artefact_id', '=', 'artefacts.id')
            ->where('thread', 'LIKE', $topic)
            ->get();

        $user = Auth::user();

        if ($user && $user->role == "editor") {
            return view('admin.data.tree', ['topics' => $topics, 'topic' => $topic, 'tree' => $tree, 'list' => $list, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }

    }

    public function topics(Request $request) {
        $topics = DB::table('artefacts')
            ->orderBy('updated_at', 'desc')
            ->whereNull('parent_id')
            ->get();
        $topic = Input::get('topic');
        if(!is_numeric($topic)) $topic = null;

        // GET PLAIN LIST of topics
        $list = DB::table('artefacts')
            ->where('parent_id', '=', null)
            ->get();

        // Get a list of tag, and connect them to a topic
        $tags = DB::table('artefacts_tags')
            ->select('artefacts.thread', 'tag_id')
            ->join('artefacts', 'artefact_id', '=', 'artefacts.id')
            ->get();

        $user = Auth::user();

        if ($user && $user->role == "editor") {
            return view('admin.data.topics', ['topics' => $topics, 'topic'=> $topic, 'list' => $list, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }
    }

    public function users(Request $request) {
        $user = Auth::user();
        /*if (!$user || $user->role != "editor") {
            App::abort(401, 'Not authenticated');
        }*/

        $users = DB::table('users')
            ->orderBy('name', 'asc')
            ->get();
        $uid = Input::get('user');
        if(!is_numeric($uid)) $user = $users[0];
        else $user = DB::table('users')
            ->where('id', $uid)
            ->get()[0];

        $user->last_contribution = DB::table('artefacts')
            ->where('author', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get();
        if($user->last_contribution) $user->last_contribution = $user->last_contribution[0];

        $user->contributions = DB::table('artefacts')
            ->where('author', $user->id)
            ->count();

        $user->topics = DB::table('artefacts')
            ->select('thread')
            ->where('author', $user->id)
            ->groupBy('thread')
            ->get();

        for($i = 0; $i < sizeof($user->topics); $i++){
            $t = $user->topics[$i];

            $thread = DB::table('artefacts')
            ->where('thread', $t->thread)
            ->where('parent_id', null)
            ->get()[0];

            $artefacts = DB::table('artefacts')
            ->where('author', $user->id)
            ->where('thread', $t->thread)
            ->get();

            $user->topics[$i]->title = $thread->title;
            $user->topics[$i]->artefacts = $artefacts;
        }

        $user->types = DB::table('artefacts')
            ->select('artefact_types.description', DB::raw('COUNT(*) AS count'))
            ->leftJoin('artefact_types', 'artefacts.artefact_type', '=', 'artefact_types.id')
            ->where('author', $user->id)
            ->groupBy('artefact_type')
            ->orderBy('count', 'desc')
            ->get();

        $user->artefacts = DB::table('artefacts')
            ->select('thread', DB::raw('COUNT(*) as count, DATE(created_at) as thedate'))
            ->where('author', $user->id)
            ->groupBy('thedate', 'thread')
            ->orderBy('thedate')
            ->get();

        return view('admin.data.users', ['users' => $users, 'user'=> $user, 'topics' => [], 'topic' => null]);
    }

    public function groups(Request $request) {

        $groups = (object) [
            (object) [
                "id" => 1,
                "name" => "teachers",
                "user_ids" => [18,26,27,28,29,30],
                "topic_ids" => []
            ],
            (object) [
                "id" => 2,
                "name" => "foto",
                "user_ids" => [143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158],
                "topic_ids" => [45]
            ],
            (object) [
                "id" => 3,
                "name" => "mixedmedia",
                "user_ids" => [31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,140,141,142],
                "topic_ids" => [30, 35, 38, 39, 146]
            ],
            (object) [
                "id" => 4,
                "name" => "slo",
                "user_ids" => [250,252,253,254,255,256,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,276,280,281,283,284,285,286,287,290,291,292,293,294],
                "topic_ids" => [43,247,248,249,250,254,256,352,353,386]
            ]
        ];

        $users = DB::table('users')
            ->select('id', 'name')
            ->get();

        $artefacts = DB::table('artefacts')
            ->select('id', 'thread', 'title', 'author')
            ->get();

        function search_id($a, $id){
            foreach($a as $i){
                if($i->id == $id) return $i;
            }
        }

        function list_author($a, $id){
            $r = [];
            foreach($a as $i){
                if($i->author == $id) array_push($r,$i);
            }
            return $r;
        }

        foreach($groups as $group){
            $group->users = [];
            foreach($group->user_ids as $user_id){
                $user = search_id($users, $user_id);
                array_push($group->users, $user);
            }
            $group->topics = [];
            foreach($group->topic_ids as $topic_id){
                $topic = search_id($artefacts, $topic_id);
                array_push($group->topics, $topic);
            }
            foreach($group->users as $user){
                $user->artefacts = list_author($artefacts, $user->id);
            }
        }

        /*
        $groups = DB::table('groups')
            ->get();

        foreach($groups as $group){
            $group->users = DB::table('users')
                ->select('id', 'name')
                ->where('group', $group->id)
                ->get();
            $group->topics = DB::table('artefacts')
                ->select('id', 'thread', 'title')
                ->where('parent_id', null)
                ->where('group', $group->id)
                ->get();
            foreach($group->users as $user){
                $user->artefacts = DB::table('artefacts')
                    ->select('id', 'title', 'thread', 'artefact_type')
                    ->where('author', $user->id)
                    ->get();
            }
        }
        */

        $user = Auth::user();
        /*if (!$user || $user->role != "editor") {
            App::abort(401, 'Not authenticated');
        }*/

        return view('admin.data.groups', ['topics' => [], 'topic' => null, 'user'=> $user, 'groups' => $groups]);
    }

    public function getThumbnails(Request $request) {
        $user = Auth::user();
        if (!$user || $user->role != "editor") {
            App::abort(401, 'Not authenticated');
        }

        // get a list of all the local pdf's & images
        $artefacts = Artefact::where('artefact_type', '29')
            ->orWhere('artefact_type', '33')
            ->get();

        // check which image sizes are available
        $basepath = base_path().'/../uploads/thumbnails';
        foreach($artefacts as $artefact){
            $sizes = Array();
            if (file_exists($basepath . '/../' . $artefact->url)) {
                array_push($sizes, 'original');
            }
            if (file_exists($basepath . '/small/' . $artefact->url)) {
                array_push($sizes, 'small');
            }
            if (file_exists($basepath . '/large/' . $artefact->url)) {
                array_push($sizes, 'large');
            }
            $artefact->setAttribute('sizes', $sizes);
        }


        return view('admin/thumbnails', ['artefacts' => $artefacts]);
    }

    public function postThumbnails(Request $request){
        try{
            if(Input::get('size') && Input::get('file') && Input::get('filename')){
                $size = Input::get('size');
                $file = Input::get('file');
                $filename = Input::get('filename');
                $destinationPath = 'uploads/thumbnails/';
                switch($size){
                    case 'small':
                        $destinationPath .= 'small/' . $filename;
                        break;
                    case 'large':
                        $destinationPath .= 'large/' . $filename;
                        break;
                    default:
                        throw new Exception ('Unrecognized file size.');
                        break;
                }
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file));
                try {
                    file_put_contents($destinationPath, $data);
                    return Response::json( [
                        'status' => '200',
                        'message' => 'Succes!'
                    ], 200);
                } catch(Exception $e){
                    throw new Exception ('Failed to store files');
                }
            } else throw new Exception ('No file, size or filename given.');
        } catch(Exception $e){
            throw $e;
        }
    }
}
