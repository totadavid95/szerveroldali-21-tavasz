<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class PostController extends Controller
{
    public function showNewPostForm() {
        return view('new-post');
    }

    public function storeNewPost(Request $request) {
        $validated = $request->validate(
            // Validation rules
            [
                'title' => 'required|min:3|max:144',
                'text' => 'required|min:3',
                'disable-comments' => 'nullable|boolean',
                'hide-post' => 'nullable|boolean',
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

        $console = new \Symfony\Component\Console\Output\ConsoleOutput();
        $console->writeln("Validated: " . json_encode($validated));

        // Store file
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $hashName = $file->hashName();
            $originalName = $file->getClientOriginalName();
            $console->writeln($hashName);
            $console->writeln($originalName);
            Storage::disk('public')->put('attachments/' . $hashName, $file->get());

            // TODO: Store original name
        }

        // ...
    }
}
