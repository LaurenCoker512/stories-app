<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class StoryChapterFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return new StoryChapter;
    }

}
