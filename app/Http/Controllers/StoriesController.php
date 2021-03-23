<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Tag;

use App\Events\StoryCreated;

use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;

class StoriesController extends Controller
{
    public function index()
    {
        $stories = Story::latest('updated_at')->take(20)->get();
        $tags = Tag::withCount('stories')->orderBy('stories_count', 'desc')->take(10)->get();

        $typeCounts = [
            'fiction' => Story::fiction()->count(),
            'nonfiction' => Story::nonfiction()->count(),
            'poetry' => Story::poetry()->count()
        ];

        return view('stories.index', compact('stories', 'tags', 'typeCounts'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('stories.create', compact('tags'));
    }

    public function store(StoreStoryRequest $request)
    {
        $validated = $request->validated();

        $story = auth()->user()->stories()->create($validated);

        if($request->has('tags')) {
            $story->updateTags(array_column($request->input('tags'), 'id'));
        }

        $story->addChapter(null, request('first_chapter'));

        $request->session()->flash('status', 'New story was posted!');

        return response()->json([
            'redirect' => $story->firstChapterPath()
        ]);
    }

    public function edit(Story $story)
    {
        $this->authorize('update', $story);

        $tags = Tag::all();

        return view('stories.edit', compact('story', 'tags'));
    }

    public function update(UpdateStoryRequest $request, Story $story)
    {
        $this->authorize('update', $story);

        $validated = $request->validated();

        $story->update($validated);

        if($request->has('tags')) {
            $story->updateTags(array_column($request->input('tags'), 'id'));
        }

        $request->session()->flash('status', 'Story was updated!');

        return response()->json([
            'redirect' => $story->firstChapterPath()
        ]);
    }

    public function destroy(Story $story)
    {
        $this->authorize('update', $story);

        $story->delete();

        return redirect('/dashboard/' . auth()->id());
    }

    public function getType(Request $request)
    {
        $type = $request->input('type');

        $stories = Story::$type()->paginate(20);

        return view('stories.type', compact('stories', 'type'));
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
