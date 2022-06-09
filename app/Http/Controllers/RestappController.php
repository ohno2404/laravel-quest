<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Movie;
class RestappController extends Controller
{
    
    public function index()
    {
        $users = User::all();
        return response()->json(
            [
                'users' => $users
            ],
            200,[],
            JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT   
            );
        
    }

  
    public function create()
    {
        return view('rest.create');
    }

  
    public function store(Request $request)
    {
        $this->validate($request,[
            'url'=> 'required|max:11',
            'comment' => 'max:36',
            ]);
        $movies = User::find(1)->movies;
        return response()->json(
            [
                'movies'=> $movies
            ],
            200,[],
                        JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT

            
            );
    }


    public function show($id)
    {
        $user = User::find($id);
        $movies = $user->movies();
        return response()->json(
            [
                'user' => $user,
            ],
            200,[],
            JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT
            );
    }

  
  
    public function destroy($id)
    {
        $movie = Movie::find($id);
        $user = $movie->user;
        if($user->id ==1){
            $movie->delete();
        }
        $movies = $user->movies();
        return response()->json(
            [
                'user' => $user,
            ],
            200,[],
            JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT
            );
    }
}
