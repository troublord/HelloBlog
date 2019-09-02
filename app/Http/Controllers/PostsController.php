<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Post as PostEloquent;
use App\PostType as PostTypeEloquent;
use Carbon\Carbon;
use Auth;
use View;
use Redirect;


class PostsController extends Controller
{

    public function __construct(){
        $this->middleware('auth', [
            'except' => [
                'index', 'show'
            ]
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = PostEloquent::orderBy('created_at', 'DESC')->paginate(5);
        return View::make('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post_types = PostTypeEloquent::orderBy('name', 'ASC')->get();
        return View::make('posts.create', compact('post_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new PostEloquent($request->all());
        $post->user_id = Auth::user()->id;
        $post->save();
        return Redirect::route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = PostEloquent::findOrFail($id);
        return View::make('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = PostEloquent::findOrFail($id);
        $post_types = PostTypeEloquent::orderBy('name' , 'ASC')->get();
        return View::make('posts.edit', compact('post', 'post_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = PostEloquent::findOrFail($id);
        $post->fill($request->all());
        $post->save();
        return Redirect::route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = PostEloquent::findOrFail($id);
        $post->delete();
        return Redirect::route('posts.index');
    }
}
