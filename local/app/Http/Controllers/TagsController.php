<?php

namespace App\Http\Controllers;

use App\User;
use App\Tags;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use Redirect;
use Illuminate\Http\Request;;
use Session;

class TagsController extends Controller
{	
	public function searchTags($start)
	{
		$tags = Tags::where('tag', 'like', $start.'%')->orderBy('times_used', 'desc')->orderBy('tag', 'asc')->lists('tag');
		
		return response()->json($tags);
	}
}