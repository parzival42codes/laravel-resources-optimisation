<?php

namespace parzival42codes\laravelResourcesOptimisation;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ResourcesServiceProvider extends PackageServiceProvider
{
    public const PACKAGE_NAME = 'laravel-resources-optimisation';

    public const PACKAGE_NAME_SHORT = 'resources-optimisation';

    public function configurePackage(Package $package): void
    {
        $package->name(self::PACKAGE_NAME)
            ->hasRoute('route');
    }

    public function registeringPackage(): void
    {
        /*
         * Register the service provider for the dependency.
         */
        $this->app->register('parzival42codes\laravelResourcesOptimisation\Providers\EventServiceProvider');
    }
}
