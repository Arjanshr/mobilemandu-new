<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Pagination\Paginator;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\OptionalAuthenticate;

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
        Route::aliasMiddleware('optional.auth', OptionalAuthenticate::class);
    }
}
