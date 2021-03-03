<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;

class StoriesController extends Controller
{
    public function index()
    {
        $stories = Story::all();

        return view('stories.index', compact('stories'));
    }

    public function show(Story $story)
    {
        return view('stories.show', compact('story'));
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required'
        ]);

        auth()->user()->stories()->create($attributes);

        return redirect('/stories');
    }
}
