<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        // 1. nacin za kreiranje kolekcije resursa
        // return PostResource::collection($posts);

        // 2. nacin - preko kolekcija
        return new PostCollection($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:100',
            'slug' => 'required|string',
            'excerpt' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'slug' => $request->slug,
            'excerpt' => $request->excerpt,
            'category_id' => $request->category_id,
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'Post je uspesno sacuvan',
            new PostResource($post)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // $post = Post::find($postid);
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        // return response()->json($request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:100',
            'excerpt' => 'required',
            'body' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails())
            return response()->json($validator->errors());

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->excerpt = $request->excerpt;
        $post->body = $request->body;
        $post->category_id = $request->category_id;

        $post->save();

        return response()->json(['Post is updated successfully.', new PostResource($post)]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json('Post je uspesno obrisan.');
    }
}