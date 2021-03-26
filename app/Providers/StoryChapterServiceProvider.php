<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StoryChapterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('story-chapter',function(){
            return new StoryChapter();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
