<?php namespace Sukohi\Evaluation;

use Illuminate\Support\ServiceProvider;

class EvaluationServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var  bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('evaluation', function(){

            return new Evaluation;

        });

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations')
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['evaluation'];
    }

}