<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Story;

class CommentsController extends Controller
{
    public function store(Story $story, int $chapterNum)
    {
        $chapter = $story->getChapterByNumber($chapterNum);

        $chapter->addComment(request('body'), auth()->id() ?? null);

        return redirect($chapter->path());
    }

    public function update(Story $story, int $chapterNum, Comment $comment)
    {
        $this->authorize('update', $comment);

        $chapter = $story->getChapterByNumber($chapterNum);

        $comment->update([
            'body' => request('body')
        ]);

        return redirect($chapter->path());
    }

    public function destroy(Story $story, int $chapterNum, Comment $comment)
    {
        $this->authorize('update', $comment);

        $chapter = $story->getChapterByNumber($chapterNum);

        $comment->delete();

        return redirect($chapter->path());
    }
}
