<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\PriceGrid;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Media;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProductOptionPolicy;
use App\Policies\PriceGridPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\SettingPolicy;
use App\Policies\MediaPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        Product::class => ProductPolicy::class,
        ProductOption::class => ProductOptionPolicy::class,
        PriceGrid::class => PriceGridPolicy::class,
        Category::class => CategoryPolicy::class,
        Setting::class => SettingPolicy::class,
        Media::class => MediaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Example gate for admin dashboard access
        Gate::define('access-admin', function ($user) {
            return $user->hasRole('super-admin') || $user->hasRole('admin');
        });
    }
}
