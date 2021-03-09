<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class PostController extends Controller
{
    public function newPostFormIndex() {
        return view('new-post');
    }

    public function store(Request $request) {
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

        $console_out = new \Symfony\Component\Console\Output\ConsoleOutput();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $file_name = $file->getClientOriginalName();
            //$console_out->writeln($file_name);
            $hash_name = $file->hashName();
            //$console_out->writeln($hash_name);
            Storage::disk('public')->put('attachments/' . $hash_name, $file->get());
        }

        $console_out->writeln("Post validated: " . json_encode($validated));


    }
}
