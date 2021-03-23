<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Story;

use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;

class ChaptersController extends Controller
{
    public function show(Story $story, int $chapterNum)
    {
        $chapter = $story->getChapterByNumber($chapterNum);

        $comments = $chapter->comments;

        $userIsSubscribed = (bool) auth()->user() && auth()->user()->subscriptions->first(function ($item, $key) use ($story) {
            return $item->subscribable_type === 'App\Models\Story' && $item->subscribable_id === $story->id;
        });

        return view('chapters.show', compact('story', 'chapter', 'comments', 'userIsSubscribed'));
    }

    public function create(Story $story)
    {
        $this->authorize('update', $story);

        return view('chapters.create', compact('story'));
    }

    public function store(StoreChapterRequest $request, Story $story)
    {
        $validated = $request->validated();

        $this->authorize('update', $story);

        $story->addChapter($validated['name'], $validated['body']);

        return response()->json([
            'redirect' => $story->firstChapterPath()
        ]);
    }

    public function edit(Story $story, int $chapterNum)
    {
        $chapter = $story->getChapterByNumber($chapterNum);

        return view('chapters.edit', compact('chapter'));
    }

    public function update(UpdateChapterRequest $request, Story $story, int $chapterNum)
    {
        $validated = $request->validated();

        $this->authorize('update', $story);

        $chapter = $story->getChapterByNumber($chapterNum);

        $chapter->update($validated);

        return response()->json([
            'redirect' => $chapter->path()
        ]);
    }

    public function destroy(Story $story, int $chapterNum)
    {
        $chapter = $story->getChapterByNumber($chapterNum);

        $this->authorize('update', $story);

        $chapter->delete();

        return redirect('/dashboard/' . auth()->id());
    }
}
