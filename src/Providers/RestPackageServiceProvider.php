<?php

namespace Jonnifil\RestPackage\Providers;

use Jonnifil\RestPackage\Console\Make\MakeRepositoryCommand;
use Jonnifil\RestPackage\Console\Make\MakeRestApiControllerCommand;
use Jonnifil\RestPackage\Console\Make\RestApiCommand;
use Jonnifil\RestPackage\Console\Make\PrintRouteCommand;
use Illuminate\Support\ServiceProvider;

class RestPackageServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            if (! file_exists(app_path('Http/Controllers/Api/ApiController.php'))) {
                $publishes[__DIR__ . '/../ApiController.php'] = app_path('Http/Controllers/Api/ApiController.php');
            }

            if (! file_exists(app_path('Http/Repositories/BaseRepository.php'))) {
                $publishes[__DIR__ . '/../BaseRepository.php'] = app_path('Repositories/BaseRepository.php');
            }

            if (! file_exists(app_path('Services/Auth/FilterMap.php'))) {
                $publishes[__DIR__ . '/../FilterMap.php'] = app_path('Services/Auth/FilterMap.php');
            }

            $this->publishes($publishes);
            $this->commands([
                MakeRepositoryCommand::class,
                MakeRestApiControllerCommand::class,
                PrintRouteCommand::class,
                RestApiCommand::class,
            ]);
        }
    }
}
