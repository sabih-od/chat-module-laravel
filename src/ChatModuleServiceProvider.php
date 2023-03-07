<?php

namespace ChatModule;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ChatModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
//        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'chat-module');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('chatmodule.php'),
                __DIR__ . '/../config/media-library.php' => config_path('media-library.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/resources/assets' => public_path('chatmodule'),
            ], 'assets');

            $migrations = [];
            if (!$this->migrationExists('_create_channels_table.php')) {
                $migrations[__DIR__ . '/../database/migrations/create_channels_table.php.stub'] = database_path('migrations/' . date('Y_m_d_His', time()) . '_create_channels_table.php');
            }
            if (!$this->migrationExists('_create_chat_messages_table.php')) {
                $migrations[__DIR__ . '/../database/migrations/create_chat_messages_table.php.stub'] = database_path('migrations/' . date('Y_m_d_His', time()) . '_create_chat_messages_table.php');
            }
            if (!$this->migrationExists('_create_media_table.php')) {
                $migrations[__DIR__ . '/../database/migrations/create_media_table.php.stub'] = database_path('migrations/' . date('Y_m_d_His', time()) . '_create_media_table.php');
            }
            if (!$this->migrationExists('_create_user_deletes_table.php')) {
                $migrations[__DIR__ . '/../database/migrations/create_user_deletes_table.php.stub'] = database_path('migrations/' . date('Y_m_d_His', time()) . '_create_user_deletes_table.php');
            }
            $this->publishes($migrations, 'migrations');

        }

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'chatmodule');
        $this->registerRoutes();
    }


    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('chatmodule.prefix'),
            'middleware' => config('chatmodule.middleware'),
        ];
    }

    protected function migrationExists($file)
    {
        $found = glob(database_path('migrations/*' . $file));
        return count($found) > 0;
    }
}