<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CustomerRepository;
use App\Repositories\AddressRepository;
use App\Models\Customer;
use App\Models\Address;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerRepository::class, function ($app) {
            return new CustomerRepository(new Customer());
        });

        $this->app->bind(AddressRepository::class, function ($app) {
            return new AddressRepository(new Address());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
