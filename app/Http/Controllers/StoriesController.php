<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Tag;

use App\Events\StoryCreated;

use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;

/**
 * This class is a controller for stories.
 */
class StoriesController extends Controller
{
    /**
     * This method gets all of the existing stories and top 10 tags and returns
     * the main stories index page (the homepage).
     * 
     * @return view
     */
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

    /**
     * This method returns the create new story form.
     * 
     * @return view
     */
    public function create()
    {
        $tags = Tag::all();
        return view('stories.create', compact('tags'));
    }

    /**
     * This method creates a new story.
     * 
     * @param StoreStoryRequest $request
     * 
     * @return json
     */
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

    /**
     * This method returns the edit page for a specific story.
     * 
     * @param Story $story An instance of the Story model
     * 
     * @return view
     */
    public function edit(Story $story)
    {
        $this->authorize('update', $story);

        $tags = Tag::all();

        return view('stories.edit', compact('story', 'tags'));
    }

    /**
     * This method updates a given story.
     * 
     * @param UpdateStoryRequest $request
     * @param Story $story An instance of the Story model
     * 
     * @return json
     */
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

    /**
     * This method deletes a given story.
     * 
     * @param Story $story An instance of the Story model
     * 
     * @return redirect
     */
    public function destroy(Story $story)
    {
        $this->authorize('update', $story);

        $story->delete();

        return redirect('/dashboard/' . auth()->id());
    }

    /**
     * This method gets all the stories of a given type.
     * 
     * @param Request $request
     * 
     * @return view
     */
    public function getType(Request $request)
    {
        $type = $request->input('type');

        $stories = Story::$type()->paginate(20);

        return view('stories.type', compact('stories', 'type'));
    }

    /**
     * This method searches through existing stories and returns those that
     * match the given query.
     * 
     * @param Request $request
     * 
     * @return view
     */
    public function search(Request $request)
    {
        $search = $request->query('query');

        $stories = Story::query()
            ->where('description', 'LIKE', "%{$search}%") 
            ->orWhere('title', 'LIKE', "%{$search}%") 
            ->paginate(20);

        return view('stories.search', compact('stories'));
    }
}
