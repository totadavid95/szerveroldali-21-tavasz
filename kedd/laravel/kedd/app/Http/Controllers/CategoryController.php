<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    const styles = ['primary','secondary','success','danger','warning','info','dark','light'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create', ['styles' => self::styles]);
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
                'name' => 'required|min:3|max:20',
                'style' => 'required|in:'.join(",", self::styles),
            ],
            // Custom messages
            [
                //'required' => 'A(z) :attribute mező megadása kötelező.',
                'name.required' => 'A név megadása kötelező.', // Csak a "name" nevű esetében
                'required' => 'A mező megadása kötelező.',
                'min' => 'A mező legalább :min hosszú legyen.'
            ]
        );

        $category = Category::create($validated);

        $request->session()->flash('category-created', $category->name);
        return redirect()->route('categories.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', ['styles' => self::styles, 'category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3|max:20',
                'style' => 'required|in:'.join(",", self::styles),
            ],
            // Custom messages
            [
                //'required' => 'A(z) :attribute mező megadása kötelező.',
                'name.required' => 'A név megadása kötelező.', // Csak a "name" nevű esetében
                'required' => 'A mező megadása kötelező.',
                'min' => 'A mező legalább :min hosszú legyen.'
            ]
        );

        $category->update($validated);

        $request->session()->flash('category-updated', $category->name);
        return redirect()->route('categories.edit', $category);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
