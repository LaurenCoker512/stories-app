<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Story;

class StoryChaptersController extends Controller
{
    public function store(Story $story)
    {
        $this->authorize('update', $story);

        request()->validate(['body' => 'required']);

        $story->addChapter(request('body'));

        return redirect($story->path());
    }

    public function update(Story $story, Chapter $chapter)
    {
        $this->authorize('update', $chapter->story);

        $chapter->update([
            'body' => request('body')
        ]);

        return redirect($chapter->story->path());
    }
}
