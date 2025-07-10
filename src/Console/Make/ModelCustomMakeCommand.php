<?php

namespace Jonnifil\RestPackage\Console\Make;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ModelCustomMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model-custom {name}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom create a new Eloquent model class';

    public function handle()
    {
        return parent::handle();
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $replace = $this->buildFactoryReplacements();

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models' : $rootNamespace;
    }

    protected function buildFactoryReplacements(): array
    {
        $replacements = [];
        $modelPath = Str::of($this->argument('name'))->studly()->replace('/', '\\')->toString();

        $factoryNamespace = '\\Database\\Factories\\'.$modelPath.'Factory';

        $factoryCode = <<<EOT
            /** @use HasFactory<$factoryNamespace> */
                use HasFactory;
            EOT;

        $replacements['{{ factory }}'] = $factoryCode;
        $replacements['{{ factoryImport }}'] = 'use Illuminate\Database\Eloquent\Factories\HasFactory;';

        return array_merge($replacements, $this->buildPhpDocReplacements());
    }

    protected function getPropertyType(string $typeName): string
    {
        if (Str::startsWith($typeName, 'int')) {
            return 'integer';
        }

        return match ($typeName) {
            'timestamp' => 'Carbon',
            'json', 'jsonb' => 'array',
            'boolean' => 'boolean',
            default => 'string',
        };
    }

    protected function buildPhpDocReplacements(): array
    {
        $replacements = [];
        $tableName = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        $replace = '';
        if (Schema::hasTable($tableName)) {
            $replace .= 'Model class for table `' . $tableName . '`';
            foreach (Schema::getColumns($tableName) as $column) {
                $replace .= PHP_EOL . ' * @property ' . $this->getPropertyType($column['type_name']) . ' $' . $column['name'] . ' ' . $column['comment'];
            }
        }

        $replacements['{{ phpDoc }}'] = $replace;

        return $replacements;
    }

    protected function getStub(): string
    {
        return __DIR__.'/stubs/custom-model.stub';
    }
}
