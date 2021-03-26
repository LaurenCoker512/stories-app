<?php

namespace App\Facades;

use App\Models\Chapter;
use App\Models\Story;

class StoryChapter
{
    public function getChapterNumFromId($chapterId)
    {
        $chapter = Chapter::findOrFail($chapterId);
        $allChapters = $chapter->story->chapters->sortBy('created_at');

        foreach($allChapters as $i=>$chap) {
            if ($chap->id === $chapter->id) {
                return $i + 1;
            }
        }
    }

    public function getChapterIdFromNum($storyId, $chapterNum)
    {
        $story = Story::findOrFail($storyId);
        $allChapters = $story->chapters->sortBy('created_at');

        foreach($allChapters as $i=>$chapter) {
            if ($i + 1 === $chapterNum) {
                return $chapter;
            }
        }
    }
}