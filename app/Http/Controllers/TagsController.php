<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name', 'desc')->paginate(20);

        return view('tags.index', compact('tags'));
    }

    public function show(Tag $tag)
    {
        $stories = $tag->stories->paginate(20);

        return view('tags.show', compact('tag', 'stories'));
    }
}
