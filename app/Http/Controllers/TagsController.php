<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Story;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name', 'desc')->paginate(20);

        return view('tags.index', compact('tags'));
    }

    public function show(Tag $tag)
    {
        $stories = Story::whereHas('tags', function($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })->paginate(20);

        return view('tags.show', compact('tag', 'stories'));
    }

    public function create()
    {
        
    }
}
