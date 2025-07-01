<?php

namespace Jonnifil\RestPackage\Console\Make;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PrintRouteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'print:rest-route {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');
        $route = Str::snake($model, '-');
        $routeRestPath = base_path('routes/rest.php');
        $exists = file_exists($routeRestPath);
        $file = fopen($routeRestPath, "a");
        if (!$exists) {
            fwrite($file, "<?php" . PHP_EOL);
        }
        fwrite($file, $this->getRouteString($route, $model));
        fclose($file);
    }

    private function getRouteString($route, $model): string
    {
        $controllerName = "App\Http\Controllers\Api\\" . $model . "Controller::class";
        return "Route::apiResource('{$route}', {$controllerName});" . PHP_EOL;
    }
}
