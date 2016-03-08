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
            return view('admin/index', ['artefacts' => $artefacts, 'users' => $users, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }
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
