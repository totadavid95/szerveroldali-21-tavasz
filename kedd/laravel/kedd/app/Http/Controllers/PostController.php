<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            // Validation rules
            [
                'title' => 'required|min:3|max:144',
                'text' => 'required|min:3',
                'disable-comments' => 'nullable|boolean',
                'hide-post' => 'nullable|boolean',
                'attachment' => 'nullable|file|mimes:txt,pdf,png,jpg|max:4096',
            ],
            // Custom messages
            [
                'name.required' => 'A név megadása kötelező.', // Csak a "name" nevű esetében
                'required' => 'A mező megadása kötelező.',
                'min' => 'A mező legalább :min hosszú legyen.'
            ]
        );

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $file_name = $file->getClientOriginalName();
            //$console_out->writeln($file_name);
            $hash_name = $file->hashName();
            //$console_out->writeln($hash_name);
            Storage::disk('public')->put('attachments/' . $hash_name, $file->get());
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
