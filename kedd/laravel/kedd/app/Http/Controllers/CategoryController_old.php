<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function newCategoryFormIndex() {
        $possible_styles = ['primary','secondary','success','danger','warning','info','dark','light'];
        return view('new-category', compact('possible_styles'));
    }

    public function store(Request $request) {
        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3|max:20',
                'style' => 'required|in:primary,secondary,success,danger,warning,info,dark,light'
            ],
            // Custom messages
            [
                //'required' => 'A(z) :attribute mező megadása kötelező.',
                'name.required' => 'A név megadása kötelező.', // Csak a "name" nevű esetében
                'required' => 'A mező megadása kötelező.',
                'min' => 'A mező legalább :min hosszú legyen.'
            ]
        );

        //$console_out = new \Symfony\Component\Console\Output\ConsoleOutput();
        //$console_out->writeln("Validated: " . json_encode($validated));

        $category = Category::create($validated);

        $request->session()->flash('category-created', $category->name);
        return redirect()->route('new-category');
    }
}
