<?php

namespace RonAppleton\Radmin\Permissions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use RonAppleton\MenuBuilder\Traits\AddsMenu;

class ModuleServiceProvider extends ServiceProvider
{
    use AddsMenu;
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'RonAppleton\Radmin\Permissions\Http\Controllers';


    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function boot(Dispatcher $events)
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadViews();
        $this->publishConfig();
        $this->menuListener($events);
    }

    public function register()
    {

    }

    private function loadViews()
    {
        $viewsPath = $this->packagePath('resources/views');

        $this->loadViewsFrom($viewsPath, 'radmin-permissions');

        $this->publishes([
            $viewsPath => base_path('resources/views/vendor/radmin-permissions'),
        ], 'views');
    }

    private function publishConfig()
    {
        $configPath = $this->packagePath('config/radmin-permissions.php');

        $this->publishes([
            $configPath => config_path('radmin-permissions.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'radmin-permissions');
    }

    private function packagePath($path)
    {
        return __DIR__ . "/../$path";
    }

    protected function loadViewsFrom($path, $namespace)
    {
        if (is_array($this->app->config['view']['paths'])) {
            foreach ($this->app->config['view']['paths'] as $viewPath) {
                if (is_dir($appPath = $viewPath . '/vendor/' . $namespace)) {
                    $this->app['view']->addNamespace($namespace, $appPath);
                }
            }
        }

        $this->app['view']->addNamespace($namespace, $path);
    }

    public function menuSidebar()
    {
        return [
            [
             
            ],
        ];
    }

}