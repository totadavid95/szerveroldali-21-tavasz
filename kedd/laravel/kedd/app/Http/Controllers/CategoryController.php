<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function newCategoryFormIndex() {
        return view('new-category');
    }

    public function store(Request $request) {
        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3|max:20',
                'style' => 'required'
            ],
            // Custom messages
            [
                //'required' => 'A(z) :attribute mező megadása kötelező.',
                'name.required' => 'A név megadása kötelező.', // Csak a "name" nevű esetében
                'required' => 'A mező megadása kötelező.',
                'min' => 'A mező legalább :min hosszú legyen.'
            ]
        );

        $console_out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $console_out->writeln("Validated: " . json_encode($validated));
    }
}
