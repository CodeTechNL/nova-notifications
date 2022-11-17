<?php

namespace CodeTech\NovaNotifications;

use CodeTech\NovaNotifications\Commands\DeleteNovaNotifications;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class NovaNotificationServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->commands([
            DeleteNovaNotifications::class
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/nova_notifications.php', 'nova_notifications');

        $this->publishes([
            __DIR__.'/../config/nova_notifications.php' => config_path('nova_notifications.php')
        ], 'nova-notifications');
    }

    /**
     * @return void
     */
    public function register()
    {

    }
}
