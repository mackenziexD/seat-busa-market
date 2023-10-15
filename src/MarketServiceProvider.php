<?php

namespace Helious\SeatBusaMarket;

use Seat\Services\AbstractSeatPlugin;


class MarketServiceProvider extends AbstractSeatPlugin
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/seat-busa-market.php', 'seat-busa-market');
        $this->mergeConfigFrom(__DIR__ . '/Config/seat-busa-market.sidebar.php', 'package.sidebar');
        $this->mergeConfigFrom(__DIR__ . '/Config/customENV.php', 'seat-busa-market-custom');
        $this->registerPermissions(__DIR__ . '/Config/seat-busa-market.permissions.php', 'seat-busa-market');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');-

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'seat-busa-market');

        // Publish migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->publishes([
            __DIR__ . '/Config/customENV.php' => config_path('seat-busa-market-custom.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/Config/notifications.alerts.php', 'notifications.alerts'
        );

    }

    /**
     * Get the package's routes.
     *
     * @return string
     */
    protected function getRouteFile()
    {
        return __DIR__.'/routes.php';
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     * @example SeAT Web
     *
     */
    public function getName(): string
    {
        return 'SeAT BUSA Market';
    }

    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/mackenziexD/seat-busa-market';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     * @example web
     *
     */
    public function getPackagistPackageName(): string
    {
        return 'seat-busa-market';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     * @example eveseat
     *
     */
    public function getPackagistVendorName(): string
    {
        return 'helious';
    }
}