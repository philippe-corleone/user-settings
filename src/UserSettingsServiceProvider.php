<?php
/**
 * Created by PhpStorm.
 * User: Corleone
 * Date: 17.02.18
 * Time: 14:57
 */

namespace Corleone\UserSettings;

use Illuminate\Support\ServiceProvider;

class UserSettingsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/config/user-settings.php' => config_path('user-settings.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserSettings::class, function () {
            return new UserSettings();
        });

        $this->app->alias(UserSettings::class, 'user-settings');

        if ($this->app->config->get('user-settings') === null) {
            $this->app->config->set('user-settings', require __DIR__ . '/config/user-settings.php');
        }
    }

}