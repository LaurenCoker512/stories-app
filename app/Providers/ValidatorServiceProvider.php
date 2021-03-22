<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('validtype', function ($attribute, $value, $parameters)
        {
            if (!in_array($value, ['fiction', 'nonfiction', 'poetry'])) {
                return false;
            }
            return true;
        });
    }
}
