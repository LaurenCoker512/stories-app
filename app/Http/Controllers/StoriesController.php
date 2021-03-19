<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Tag;

use App\Events\StoryCreated;

class StoriesController extends Controller
{
    public function index()
    {
        $stories = Story::latest('updated_at')->take(20)->get();
        $tags = Tag::withCount('stories')->orderBy('stories_count', 'desc')->take(10)->get();

        return view('stories.index', compact('stories', 'tags'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('stories.create', compact('tags'));
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required'
        ]);

        $story = auth()->user()->stories()->create($attributes);

        // TODO: Add tags

        $story->addChapter(request('first-chapter'));

        return redirect($story->firstChapterPath());
    }

    public function edit(Story $story)
    {
        $this->authorize('update', $story);

        $tags = Tag::all();

        return view('stories.edit', compact('story', 'tags'));
    }

    public function update(Story $story)
    {
        $this->authorize('update', $story);

        $attributes = request()->validate([
            'title' => 'sometimes|required|min:1', 
            'description' => 'sometimes|required|min:1'
        ]);

        $story->update($attributes);

        return redirect($story->firstChapterPath());
    }

    public function destroy(Story $story)
    {
        $this->authorize('update', $story);

        $story->delete();

        return redirect('/dashboard/' . auth()->id());
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $stories = Story::query()
            ->where('description', 'LIKE', "%{$search}%") 
            ->orWhere('title', 'LIKE', "%{$search}%") 
            ->paginate(20);

        return view('stories.search', compact('stories'));
    }
}
