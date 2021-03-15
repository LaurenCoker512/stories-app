<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Story;

class StoryChaptersController extends Controller
{
    public function show(Story $story, int $chapterNum)
    {
        $chapter = $story->getChapterByNumber($chapterNum);

        return view('chapters.show', compact('story', 'chapter'));
    }

    public function create(Story $story)
    {
        $this->authorize('update', $story);

        return view('chapters.create', compact('story'));
    }

    public function store(Story $story)
    {
        $this->authorize('update', $story);

        request()->validate(['body' => 'required']);

        $story->addChapter(request('body'));

        return redirect($story->firstChapterPath());
    }

    public function edit(Story $story, int $chapterNum)
    {
        $chapter = $story->getChapterByNumber($chapterNum);

        return view('chapters.edit', compact('chapter'));
    }

    public function update(Story $story, int $chapterNum)
    {
        $chapter = $story->getChapterByNumber($chapterNum);

        $this->authorize('update', $chapter->story);

        $chapter->update([
            'body' => request('body')
        ]);

        return redirect($story->firstChapterPath());
    }

    public function destroy(Story $story, int $chapterNum)
    {
        $chapter = $story->getChapterByNumber($chapterNum);

        $this->authorize('update', $story);

        $chapter->delete();

        return redirect('/dashboard/' . auth()->id());
    }
}
