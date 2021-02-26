<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showNewCategoryForm() {
        $styles = [
            'primary',
            'secondary',
            'success',
            'danger',
            'warning',
            'info',
            'light',
            'dark',
        ];

        return view('new-category', compact('styles'));
    }

    public function storeNewCategory(Request $request) {
        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3',
                'style' => 'required|in:primary,secondary,success,danger,warning,info,light,dark',
            ],
            // Custom messages
            [
                'name.required' => 'A nevet meg kell adni.',
                'name.min' => 'A név legalább :min karakter legyen.',
                'required' => 'A(z) :attribute mezőt meg kell adni.',
            ]
        );

        $console = new \Symfony\Component\Console\Output\ConsoleOutput();
        $console->writeln("Validated: " . json_encode($validated));

        // ...
    }
}
