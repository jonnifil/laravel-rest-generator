<?php

namespace App\Console\Commands\Make;

use Illuminate\Console\Command;

class RestApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:rest-api {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new REST by model name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('name');
        $controller = $model . 'Controller';

        $this->call('make:rest-api-controller', ['name' => $controller, '--model' => $model]);
    }
}
