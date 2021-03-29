<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Story;

/**
 * This class is a controller for tags on stories.
 */
class TagsController extends Controller
{
    /**
     * This method gets all the existing tags and returns a view with them.
     * 
     * @return view
     */
    public function index()
    {
        $tags = Tag::orderBy('name', 'desc')->paginate(20);

        return view('tags.index', compact('tags'));
    }

    /**
     * This method gets all of the stories associated with a given tag.
     * 
     * @param Tag $tag An instance of the Tag model
     * 
     * @return view
     */
    public function show(Tag $tag)
    {
        $stories = Story::whereHas('tags', function($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })->paginate(20);

        return view('tags.show', compact('tag', 'stories'));
    }

    /**
     * This method creates a new tag.
     * 
     * @return json
     */
    public function store()
    {
        $tag = Tag::firstOrCreate(['name' => request('name')]);

        return response()->json([
            'id' => $tag->id,
            'name' => $tag->name
        ]);
    }

    /**
     * This method searches existing tags for a given query and returns any
     * matching tags.
     * 
     * @return Collection
     */
    public function search()
    {
        $query = urldecode(request('query'));

        return Tag::query()
            ->where('name', 'LIKE', "%{$query}%") 
            ->get()
            ->toJson();
    }
}
