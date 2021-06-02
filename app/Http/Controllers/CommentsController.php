<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Facades\StoryChapterFacade;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Story;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

/**
 * This class is a controller for comments for chapters.
 */
class CommentsController extends Controller
{
    /**
     * This method saves a new comment on a chapter.
     * 
     * @param StoreCommentRequest $request
     * @param Story $story An instance of the Story model
     * @param int $chapterNum The number of the chapter, which will find an
     * instance of Chapter based on when the story's chapters were created
     * 
     * @return redirect
     */
    public function store(StoreCommentRequest $request, Story $story, int $chapterNum)
    {
        $validated = $request->validated();

        $chapter = StoryChapterFacade::getChapterIdFromNum($story->id, $chapterNum);

        $chapter->addComment(request('body'), auth()->id() ?? null);

        $request->session()->flash('status', 'Comment was posted!');

        return redirect($chapter->path());
    }

    /**
     * This method updates a comment on a chapter.
     * 
     * @param UpdateCommentRequest $request
     * @param Story $story An instance of the Story model
     * @param int $chapterNum The number of the chapter, which will find an
     * instance of Chapter based on when the story's chapters were created
     * @param Comment $comment An instance of the Comment model
     * 
     * @return redirect
     */
    public function update(UpdateCommentRequest $request, Story $story, int $chapterNum, Comment $comment)
    {
        $validated = $request->validated();

        $this->authorize('update', $comment);

        $chapter = StoryChapterFacade::getChapterIdFromNum($story->id, $chapterNum);

        $comment->update([
            'body' => request('body')
        ]);

        $request->session()->flash('status', 'Comment was updated!');

        return redirect($chapter->path());
    }

    /**
     * This method deletes a comment from a chapter.
     * 
     * @param Story $story An instance of the Story model
     * @param int $chapterNum The number of the chapter, which will find an
     * instance of Chapter based on when the story's chapters were created
     * @param Comment $comment An instance of the Comment model
     * 
     * @return redirect
     */
    public function destroy(Story $story, int $chapterNum, Comment $comment)
    {
        $this->authorize('update', $comment);

        $chapter = StoryChapterFacade::getChapterIdFromNum($story->id, $chapterNum);

        $comment->delete();

        return redirect($chapter->path());
    }

    public function getComments(Story $story, int $chapterNum)
    {
        $chapter = StoryChapterFacade::getChapterIdFromNum($story->id, $chapterNum);

        $comments = Comment::where('chapter_id', $chapter->id)->orderBy('created_at', 'desc')->paginate(10);

        foreach ($comments as $comment) {
            $comment->can_update = $comment->id === auth()->id();
            $comment->path = $comment->path();
            $comment->avatar = $comment->author ? $comment->author->getUserAvatar() : "/img/avatar-placeholder.png";
            $comment->posted_time = \Carbon\Carbon::parse($comment->created_at)->diffForHumans();
            $comment->author = $comment->author();
        }

        return response()->json([
            'comments' => $comments
        ]);
    }
}
