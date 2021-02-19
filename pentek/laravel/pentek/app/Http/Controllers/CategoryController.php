<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showNewCategoryForm() {
        return view('new-category');
    }

    public function storeNewCategory(Request $request) {
        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3',
                'style' => 'required',
            ],
            // Custom messages
            [
                'name.required' => 'A nevet meg kell adni.',
                'name.min' => 'A név legalább :min karakter legyen.',
                'required' => 'A(z) :attribute mezőt meg kell adni.',
            ]
        );

        // ...
    }
}
