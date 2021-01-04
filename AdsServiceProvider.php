<?php

namespace Sanjok\Ads;

use Sanjok\Blog\Contracts\IAds;
use Sanjok\Blog\Contracts\IPost;
use Sanjok\Blog\Contracts\IUser;
use Illuminate\Support\Facades\App;
use Sanjok\Blog\Contracts\ICategory;
use Illuminate\Support\ServiceProvider;
use Sanjok\Blog\Repositories\AdsRepository;
use Sanjok\Blog\Repositories\PostRepository;
use Sanjok\Blog\Repositories\UserRepository;
use Sanjok\Blog\Repositories\CategoryRepository;
use Sanjok\Blog\Console\Commands\ListConfigVariables;

class AdsServiceProvider extends ServiceProvider
{
    /**
     * This method is called after all other service providers have been registered,
     * meaning you have access to all other services that have been registered by the framework:
     * Perform post-registration booting of services.
     * In the boot method, we put the code to allow the developer to export the config options by simply writing:
     * php artisan vendor:publish -tag=laravel-log-enhancer-config
     * In the service provider’s boot() method, we can use the publish() method to define the files that the user may override:
     * @return void
     */
    public function boot()
    {

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sanjok');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'Blog');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/frontendApi.php');

        // include __DIR__.'/Macros/MacroServiceProvider.php';
        require __DIR__ . '/../helpers/functions.php';
        require __DIR__ . '/../helpers/helpers.php';


        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     * in the register method, we tell the Laravel app to add the config options from our file into the web app config.
     * To merge to config automatically to the application’s config, in the package service provider’s register() method we need to add the following code:
     * From this point, we can reference this config file application-wide: config(‘package-name.something’).
     * Now the question is, what if the user needs to override some option? We need to make the config available for the users.
     * Also, we have the same issue with blade templates for example. Fortunately, we can easily do it, by allowing the users to publish
     * – or copy if you wish – the overridable files from the package to the application.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blog.php', 'blog');

        // Register the service the package provides.
        $this->app->singleton('Blog', function ($app) {
            return new Blog;
        });

        $this->bindings();
    }

    public function bindings()
    {
        App::bind(IPost::class, PostRepository::class);
        App::bind(ICategory::class, CategoryRepository::class);
        App::bind(IUser::class, UserRepository::class);
        App::bind(IAds::class, AdsRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Blog'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/blog.php' => config_path('blog.php'),
        ], 'Blog.config');

        // Publishing the views.
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/sanjok'),
        ], 'Blog.views');

        $this->publishes([
            __DIR__ . '/../Helpers/helper.php' => base_path('app'),
        ], 'Blog.helper');

        // Publishing assets.
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('assets'),
        ], 'Blog.assets');

        // Publishing the translation files.
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/sanjok'),
        ], 'Blog.lang');

        // Registering package commands.
        $this->commands([
            ListConfigVariables::class,
        ]);
    }
}
