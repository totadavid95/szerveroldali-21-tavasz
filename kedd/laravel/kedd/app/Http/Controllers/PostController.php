<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Storage;
use Auth;
use Gate;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(6);
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
                'categories' => 'nullable',
                'categories.*' => 'integer|distinct|exists:categories,id',
                'disable_comments' => 'nullable|boolean',
                'hide_post' => 'nullable|boolean',
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
            $validated['attachment_original_name'] = $file->getClientOriginalName();
            //$console_out->writeln($file_name);
            $validated['attachment_hash_name'] = $file->hashName();
            //$console_out->writeln($hash_name);
            Storage::disk('public')->put('attachments/' . $validated['attachment_hash_name'], $file->get());
        }

        $validated['author_id'] = Auth::id();

        $post = Post::create($validated);

        $post->categories()->attach($request->categories);

        $request->session()->flash('post-created', $post->title);
        return redirect()->route('posts.create');

    }

    public function attachment($id) {
        $post = Post::find($id);
        if (!$post) return abort(404);

        if (!$post->attachment_hash_name || !$post->attachment_original_name) return abort(404);

        return Storage::disk('public')->download('attachments/' . $post->attachment_hash_name, $post->attachment_original_name);
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
        //if (Gate::denies('update-post', $post)) {
        if (Auth::user()->cannot('update', $post)) {
            return abort(403);
        }
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
