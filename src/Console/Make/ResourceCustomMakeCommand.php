<?php

namespace Jonnifil\RestPackage\Console\Make;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ResourceCustomMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:resource-custom {name} {--model}';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    protected function getStub()
    {
        return __DIR__.'/stubs/custom-resource.stub';
    }

    protected function buildClass($name)
    {
        $replace = $this->buildColumnsReplacements();

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected function buildColumnsReplacements(): array
    {
        $columns = '//';
        $tableName = Str::snake(Str::pluralStudly(class_basename($this->option('model'))));
        if (Schema::hasTable($tableName)) {
            $columnsArr = [];
            foreach (Schema::getColumnListing($tableName) as $column) {
                $columnsArr[] = "'"."$column"."' => ".'$this->'.$column.',';
            }

            $columns = implode(PHP_EOL . '            ', $columnsArr);
        }

        return ['{{ columns }}' => $columns];
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Resources';
    }
}
