<?php

namespace Jonnifil\RestPackage\Providers;

use Illuminate\Support\ServiceProvider;

class RestPackageServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $publishes = [];
            if (! file_exists(app_path('Http/Controllers/Api/ApiController.php'))) {
                $publishes[__DIR__ . '/../ApiController.php'] = app_path('Http/Controllers/Api/ApiController.php');
            }

            if (! file_exists(app_path('Http/Repositories/BaseRepository.php'))) {
                $publishes[__DIR__ . '/../BaseRepository.php'] = app_path('Repositories/BaseRepository.php');
            }

            $this->publishes($publishes);
        }
    }
}
