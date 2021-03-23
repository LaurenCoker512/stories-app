<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Story;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentsController extends Controller
{
    public function store(StoreCommentRequest $request, Story $story, int $chapterNum)
    {
        $validated = $request->validated();

        $chapter = $story->getChapterByNumber($chapterNum);

        $chapter->addComment(request('body'), auth()->id() ?? null);

        $request->session()->flash('status', 'Comment was posted!');

        return redirect($chapter->path());
    }

    public function update(UpdateCommentRequest $request, Story $story, int $chapterNum, Comment $comment)
    {
        $validated = $request->validated();

        $this->authorize('update', $comment);

        $chapter = $story->getChapterByNumber($chapterNum);

        $comment->update([
            'body' => request('body')
        ]);

        $request->session()->flash('status', 'Comment was updated!');

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
