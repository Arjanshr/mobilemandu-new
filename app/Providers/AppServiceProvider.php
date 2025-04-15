<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Pagination\Paginator;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Meilisearch\Client;
use Laravel\Scout\EngineManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Dispatcher $events): void
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $count_orders = Order::where('status', '=', 'pending')->count();
            $event->menu->addAfter('search',[
                'can' => 'browse-orders',
                'key' => 'manage_orders',
                'text' => 'Manage Orders',
                'url' => 'admin/orders',
                'label' => $count_orders,
                'label_color' => 'success',
                'icon'    => 'fas fa-shopping-cart',
            ]);
        });
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
        Paginator::useBootstrap();

        resolve(EngineManager::class)->extend('meilisearch', function () {
            return new \Laravel\Scout\Engines\MeilisearchEngine(
                new Client(
                    config('scout.meilisearch.host'),
                    config('scout.meilisearch.key')
                )
            );
        });
    }
}
