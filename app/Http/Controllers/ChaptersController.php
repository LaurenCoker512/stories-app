<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Facades\StoryChapterFacade;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Story;

use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;

/**
 * This class is a controller for chapters within stories.
 */
class ChaptersController extends Controller
{
    /**
     * This method shows a single chapter of a story.
     * 
     * @param Story $story An instance of the Story model
     * @param int $chapterNum The number of the chapter, which will find an
     * instance of Chapter based on when the story's chapters were created
     * 
     * @return view
     */
    public function show(Story $story, int $chapterNum)
    {
        $chapter = StoryChapterFacade::getChapterIdFromNum($story->id, $chapterNum);

        $comments = $chapter->comments;

        $userIsSubscribed = (bool) auth()->user() && auth()->user()->subscriptions->first(function ($item, $key) use ($story) {
            return $item->subscribable_type === 'App\Models\Story' && $item->subscribable_id === $story->id;
        });

        return view('chapters.show', compact('story', 'chapter', 'comments', 'userIsSubscribed'));
    }

    /**
     * This method shows the create chapter view for a story.
     * 
     * @param Story $story An instance of the Story model
     * 
     * @return view
     */
    public function create(Story $story)
    {
        $this->authorize('update', $story);

        return view('chapters.create', compact('story'));
    }

    /**
     * This method creates a new chapter within a story.
     * 
     * @param StoreChapterRequest $request
     * @param Story $story An instance of the Story model
     * 
     * @return json
     */
    public function store(StoreChapterRequest $request, Story $story)
    {
        $validated = $request->validated();

        $this->authorize('update', $story);

        $story->addChapter($validated['name'], $validated['body']);

        $request->session()->flash('status', 'New chapter was posted!');

        return response()->json([
            'redirect' => $story->firstChapterPath()
        ]);
    }

    /**
     * This method shows the edit view for a single chapter in a story.
     * 
     * @param Story $story An instance of the Story model
     * @param int $chapterNum The number of the chapter, which will find an
     * instance of Chapter based on when the story's chapters were created
     * 
     * @return view
     */
    public function edit(Story $story, int $chapterNum)
    {
        $chapter = StoryChapterFacade::getChapterIdFromNum($story->id, $chapterNum);

        return view('chapters.edit', compact('chapter'));
    }

    /**
     * This method updates a single chapter within a story.
     * 
     * @param UpdateChapterRequest $request
     * @param Story $story An instance of the Story model
     * @param int $chapterNum The number of the chapter, which will find an
     * instance of Chapter based on when the story's chapters were created
     * 
     * @return json
     */
    public function update(UpdateChapterRequest $request, Story $story, int $chapterNum)
    {
        $validated = $request->validated();

        $this->authorize('update', $story);

        $chapter = StoryChapterFacade::getChapterIdFromNum($story->id, $chapterNum);

        $chapter->update($validated);

        $request->session()->flash('status', 'Chapter was updated!');

        return response()->json([
            'redirect' => $chapter->path()
        ]);
    }

    /**
     * This method deletes a chapter from a story.
     * 
     * @param Story $story An instance of the Story model
     * @param int $chapterNum The number of the chapter, which will find an
     * instance of Chapter based on when the story's chapters were created
     * 
     * @return redirect
     */
    public function destroy(Story $story, int $chapterNum)
    {
        $chapter = StoryChapterFacade::getChapterIdFromNum($story->id, $chapterNum);

        $this->authorize('update', $story);

        $chapter->delete();

        return redirect('/dashboard/' . auth()->id());
    }
}
