<?php

namespace App\Providers;

use App\Contracts\Repositories\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, \App\Repositories\BaseCrudRepository::class);

        foreach (glob(app_path('Repositories/*Repository.php')) as $repository) {
            $name = basename($repository, '.php');
            $class = 'App\\Repositories\\'.$name;
            $interface = 'App\\Contracts\\Repositories\\'.$name.'Interface';

            if (interface_exists($interface)) {
                $this->app->bind($interface, $class);
            }
        }
    }

    public function boot(): void
    {
    }
}