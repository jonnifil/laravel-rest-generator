<?php

namespace Jonnifil\RestPackage\Console\Make;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RequestCustomMakeCommand extends RequestMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:request-custom {name} {--model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    protected function getStub()
    {
        return __DIR__.'/stubs/custom-request.stub';
    }

    protected function buildClass($name)
    {
        $replace = $this->buildRulesReplacements();

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected function buildRulesReplacements(): array
    {
        $replacements = [];
        $rules = '//';
        $tableName = Str::snake(Str::pluralStudly(class_basename($this->option('model'))));
        if (Schema::hasTable($tableName)) {
            $rulesArr = [];
            foreach (Schema::getColumns($tableName) as $column) {
                if ($column['auto_increment'] || in_array($column['name'], ['created_at', 'updated_at'])) {
                    continue;
                }
                $rulesArr[] = $this->getColumnRules($column);
            }
            $rules = implode(PHP_EOL . "            ", $rulesArr);
        }

        $replacements['{{ rulesData }}'] = $rules;

        return $replacements;
    }

    protected function getColumnRules(array $column): string
    {
        $rule = '';
        $rule .= Str::startsWith(class_basename($this->argument('name')), "Update") ? 'sometimes|' : '';
        if (!$column['nullable']) {
            $rule .= 'required|';
        } else {
            $rule .= 'nullable|';
        }

        if (Str::startsWith($column['type_name'], 'int')) {
            $type = 'integer';
        } else {
            $type = match ($column['type_name']) {
                'jsonb', 'json' => 'array',
                'datetime', 'timestamp' => 'datetime',
                'date' => 'date',
                default => 'string'
            };
        }

        $rule .= $type;

        return "'{$column['name']}' => '{$rule}',";
    }
}
