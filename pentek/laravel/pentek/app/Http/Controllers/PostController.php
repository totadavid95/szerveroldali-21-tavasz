<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Storage;

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
        return view('posts.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            // Validation rules
            [
                'title' => 'required|min:3|max:144',
                'text' => 'required|min:3',
                'categories' => 'nullable',
                'categories.*' => 'integer|distinct|exists:categories,id',
                'disable_comments' => 'nullable|boolean',
                'hide_post' => 'nullable|boolean',
                'attachment' => 'nullable|file|mimes:pdf,txt,jpg,png,bmp|max:4096',
                //'style' => 'required|in:primary,secondary,success,danger,warning,info,light,dark',
            ],
            // Custom messages
            [
                'title.required' => 'A címet meg kell adni.',
                'title.min' => 'A cím legalább :min karakter legyen.',
                'text.min' => 'A szöveg legalább :min karakter legyen.',
                'required' => 'A(z) :attribute mezőt meg kell adni.',
            ]
        );

        // Store file
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $hashName = $file->hashName();
            $originalName = $file->getClientOriginalName();
            Storage::disk('public')->put('attachments/' . $hashName, $file->get());
            $data['attachment_hash_name'] = $hashName;
            $data['attachment_original_name'] = $originalName;
        }

        $post = Post::create($data);

        $post->categories()->attach($request->categories);

        $request->session()->flash('post-created', $post->title);
        return redirect()->route('posts.create');
    }

    public function attachment($id) {
        $post = Post::find($id);
        if (!$post) return abort(404);

        if (!$post->attachment_hash_name || !$post->attachment_original_name) return abort(404);

        return Storage::disk('public')->download(
            'attachments/' . $post->attachment_hash_name,   // disk-en hol van a fájlom
            $post->attachment_original_name,                // milyen néven töltse le
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
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
