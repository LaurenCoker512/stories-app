<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Story;

class StoryChaptersController extends Controller
{
    public function store(Story $story)
    {
        if (auth()->user()->isNot($story->user)) {
            abort(403);
        }

        request()->validate(['body' => 'required']);

        $story->addChapter(request('body'));

        return redirect($story->path());
    }

    public function update(Story $story, Chapter $chapter)
    {
        if (auth()->user()->isNot($chapter->$story->user)) {
            abort(403);
        }

        request()->validate(['body' => 'required']);

        $chapter->update([
            'body' => request('body')
        ]);

        return redirect($chapter->$story->path());
    }
}
