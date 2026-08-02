<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module {schema : module schema file name inside database/modules, e.g. customer} {--migrate : run the generated migration}';

    protected $description = 'Generate an ERP module (migration, model, repository, service, requests, routes, controller, views, permissions, menu)';

    protected array $schema = [];

    protected const PL = '@@PLURAL@@';
    protected const CL = '@@CLASS@@';
    protected const SN = '@@SINGLE@@';
    protected const ROOT = '@@ROOT@@';
    protected const COLS_JSON = '@@COLUMNS_JSON@@';
    protected const RULES = '@@RULES@@';
    protected const RELS_INIT = '@@RELS_INIT@@';
    protected const RELS_VIEW = '@@RELS_VIEW@@';
    protected const FIELDS_HTML = '@@FIELDS_HTML@@';
    protected const SOFTDEL = '@@SOFTDEL@@';
    protected const COLUMNS_BLOCK = '@@COLUMNS_BLOCK@@';
    protected const LABEL = '@@LABEL@@';
    protected const ICON = '@@ICON@@';

    public function handle(): int
    {
        foreach ($this->requiredDirs() as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }

        $this->data = require database_path('modules/'.$this->argument('schema').'.php');

        $this->info('Scaffolding module: '.$this->class());

        $this->genMigration();
        $this->genModel();
        $this->genRepository();
        $this->genService();
        $this->genRequests();
        $this->genRoutes();
        $this->genController();
        $this->genViews();
        $this->registerPermissions();
        $this->registerMenu();

        $this->newLine(2);
        $this->warn('Next: php artisan migrate (or rerun with --migrate), then hand-tune models/views/rules.');

        if ($this->option('migrate')) {
            $this->call('migrate', ['--force' => true]);
        }

        return self::SUCCESS;
    }

    /* ------------------------------------------------------------------ */
    /* accessors                                                           */
    /* ------------------------------------------------------------------ */

    protected function class(): string
    {
        return Str::studly($this->data['name']);
    }

    protected function plural(): string
    {
        return $this->data['table'];
    }

    protected function single(): string
    {
        return Str::singular($this->data['table']);
    }

    protected function softDeletes(): bool
    {
        return (bool) ($this->data['soft_deletes'] ?? false);
    }

    protected function group(): string
    {
        return $this->data['group'] ?? 'Misc';
    }

    protected function groupIcon(): string
    {
        return $this->data['menu_icon'] ?? 'fa-folder-open';
    }

    protected function icon(): string
    {
        return $this->data['icon'] ?? 'fa-circle';
    }

    protected function label(): string
    {
        return $this->data['label'] ?? Str::title(str_replace('_', ' ', $this->plural()));
    }

    protected function fields(): array
    {
        return $this->data['fields'];
    }

    protected function permissionRoot(): string
    {
        return $this->data['permission_root'] ?? $this->plural();
    }

    protected function v(string $value): string
    {
        return $value;
    }

    protected function requiredDirs(): array
    {
        return [
            app_path('Models'),
            app_path('Contracts/Repositories'),
            app_path('Repositories'),
            app_path('Services'),
            app_path('Http/Requests'),
            app_path('Http/Controllers'),
            database_path('modules'),
        ];
    }

    /* ------------------------------------------------------------------ */
    /* migration                                                           */
    /* ------------------------------------------------------------------ */

    protected function columnDef(array $f): string
    {
        $n = "'".$f['name']."'";
        $t = $f['type'];
        $call = null;

        switch ($t) {
            case 'string':
            case 'email':
                $call = 'string('.$n.', '.($f['length'] ?? 255).')';
                break;
            case 'text':
            case 'longText':
            case 'float':
            case 'boolean':
            case 'date':
            case 'dateTime':
            case 'time':
            case 'timestamp':
            case 'json':
                $call = $t.'('.$n.')';
                break;
            case 'decimal':
                $call = 'decimal('.$n.', '.($f['precision'] ?? 15).', '.($f['scale'] ?? 2).')';
                break;
            case 'integer':
            case 'bigInteger':
            case 'smallInteger':
            case 'tinyInteger':
                $call = $t.'('.$n.')';
                break;
            case 'enum':
                $opts = collect($f['options'])->map(fn ($o) => "'".$o."'")->implode(', ');
                $call = 'enum('.$n.', ['.$opts.'])';
                break;
            case 'foreignId':
                $call = 'foreignId('.$n.')';
                break;
        }

        if (! $call) {
            return '';
        }

        $col = '$table->'.$call;
        if (! empty($f['nullable'])) {
            $col .= '->nullable()';
        }
        if (array_key_exists('default', $f)) {
            $col .= '->default('.var_export($f['default'], true).')';
        }
        if (! empty($f['unique'])) {
            $col .= '->unique()';
        }
        if ($t === 'foreignId' && ! empty($f['relation']['table']) && ($f['relation']['constrain'] ?? false)) {
            $col .= '->constrained('.$f['relation']['table'].')';
        }

        return $col.';';
    }

    protected function genMigration(): void
    {
        $existing = glob(database_path('migrations/*_create_'.$this->plural().'_table.php'));
        if ($existing) {
            $this->warn('Migration already present ('.count($existing).') — skipping.');

            return;
        }

        $file = database_path('migrations/'.date('Y_m_d_His').'_create_'.$this->plural().'_table.php');

        $blocks = '';
        foreach ($this->fields() as $f) {
            $blocks .= '            '.$this->columnDef($f).PHP_EOL;
        }

        $softDel = $this->softDeletes() ? '$table->softDeletes();' : '';

        $tpl = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('@@PLURAL@@', function (Blueprint $table) {
            $table->id();
@@COLUMNS@@
            $table->timestamps();
            @@SOFTDEL@@
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('@@PLURAL@@');
    }
};
PHP;

        $tpl = strtr($tpl, [
            self::PL => $this->plural(),
            '@@COLUMNS@@' => rtrim($blocks),
            self::SOFTDEL => $softDel,
        ]);

        file_put_contents($file, $tpl);
        $this->info('Migration created.');
    }

    /* ------------------------------------------------------------------ */
    /* model                                                               */
    /* ------------------------------------------------------------------ */

    protected function modelImports(): string
    {
        $imports = ['use Illuminate\Database\Eloquent\Model;'];
        foreach ($this->fields() as $f) {
            if (($f['type'] ?? '') === 'foreignId' && isset($f['relation']['model'])) {
                $imports[] = 'use App\Models\\'.Str::studly($f['relation']['model']).';';
            }
        }
        if ($this->softDeletes()) {
            $imports[] = 'use Illuminate\Database\Eloquent\SoftDeletes;';
        }

        return implode(PHP_EOL, $imports);
    }

    protected function modelCasts(): string
    {
        $casts = [];
        foreach ($this->fields() as $f) {
            $cast = match ($f['type']) {
                'decimal' => 'decimal:2',
                'boolean' => 'boolean',
                'date' => 'date',
                'dateTime', 'timestamp' => 'datetime',
                'json' => 'array',
                default => null,
            };
            if ($cast) {
                $casts[] = "'".$f['name']."' => '".$cast."'";
            }
        }

        return empty($casts)
            ? ''
            : "    protected \$casts = [\n        ".implode(",\n        ", $casts).",\n    ];";
    }

    protected function relationsCode(): string
    {
        $code = '';
        foreach ($this->fields() as $f) {
            if (($f['type'] ?? '') !== 'foreignId' || empty($f['relation'])) {
                continue;
            }
            $method = $f['relation']['name'] ?? Str::camel((string) Str::before($f['name'], '_id'));
            $model = Str::studly($f['relation']['model']);
            $ownerKey = $f['relation']['ownerKey'] ?? 'id';
            $code .= "    public function {$method}()\n    {\n        return \$this->belongsTo({$model}::class, '{$f['name']}', '{$ownerKey}');\n    }\n\n";
        }

        return rtrim($code);
    }

    protected function genModel(): void
    {
        $file = app_path('Models/'.$this->class().'.php');
        if (file_exists($file)) {
            $this->warn('Model exists — skipping.');

            return;
        }

        $fillable = collect($this->fields())->map(fn ($f) => "'".$f['name']."'")->implode(', ');

        $body =
            '<?php'.PHP_EOL
            .PHP_EOL
            .'namespace App\Models;'.PHP_EOL
            .PHP_EOL
            .$this->modelImports().PHP_EOL
            .PHP_EOL
            .'class '.$this->class().' extends Model'.PHP_EOL
            .'{'.PHP_EOL
            .($this->softDeletes() ? "    use SoftDeletes;\n\n" : '')
            ."    protected \$table = '".$this->plural()."';".PHP_EOL
            .PHP_EOL
            .'    protected $fillable = ['.PHP_EOL
            .'        '.$fillable.PHP_EOL
            .'    ];'.PHP_EOL
            .PHP_EOL
            .$this->modelCasts().PHP_EOL
            .PHP_EOL
            .$this->relationsCode().PHP_EOL
            .'}'.PHP_EOL;

        file_put_contents($file, $body);
        $this->info('Model created.');
    }

    /* ------------------------------------------------------------------ */
    /* repository / service / requests                                     */
    /* ------------------------------------------------------------------ */

    protected function genRepository(): void
    {
        $class = $this->class();
        $interfacePath = app_path('Contracts/Repositories/'.$class.'RepositoryInterface.php');
        $repoPath = app_path('Repositories/'.$class.'Repository.php');

        if (file_exists($interfacePath) && file_exists($repoPath)) {
            $this->warn('Repository exists — skipping.');

            return;
        }

        $interface = <<<PHP
<?php

namespace App\Contracts\Repositories;

use App\Contracts\Repositories\RepositoryInterface;

interface {$class}RepositoryInterface extends RepositoryInterface
{
}
PHP;

        $repo = <<<PHP
<?php

namespace App\Repositories;

use App\Contracts\Repositories\\{$class}RepositoryInterface;
use App\Models\\{$class};

class {$class}Repository extends BaseCrudRepository implements {$class}RepositoryInterface
{
    public function model(): string
    {
        return {$class}::class;
    }
}
PHP;

        file_put_contents($interfacePath, $interface.PHP_EOL);
        file_put_contents($repoPath, $repo.PHP_EOL);
        $this->info('Repository + interface created.');
    }

    protected function genService(): void
    {
        $class = $this->class();
        $file = app_path('Services/'.$class.'Service.php');

        if (file_exists($file)) {
            $this->warn('Service exists — skipping.');

            return;
        }

        $content = <<<PHP
<?php

namespace App\Services;

use App\Contracts\Repositories\\{$class}RepositoryInterface;
use App\Models\\{$class};

class {$class}Service
{
    public function __construct(protected {$class}RepositoryInterface \$repository)
    {
    }

    public function query()
    {
        return \$this->repository->query();
    }

    public function find(int \$id): ?{$class}
    {
        return \$this->repository->find(\$id);
    }

    public function create(array \$attributes): {$class}
    {
        return \$this->repository->create(\$attributes);
    }

    public function update(int \$id, array \$attributes): {$class}
    {
        return \$this->repository->update(\$id, \$attributes);
    }

    public function delete(int \$id): bool
    {
        return \$this->repository->delete(\$id);
    }
}
PHP;

        file_put_contents($file, $content.PHP_EOL);
        $this->info('Service created.');
    }

    protected function rulesFor(bool $update): string
    {
        $lines = [];
        foreach ($this->fields() as $f) {
            if (! array_key_exists('form', $f) && ! array_key_exists('rule', $f)) {
                continue;
            }

            $base = $f['rule'] ?? '';
            $required = (bool) ($f['required'] ?? false);

            if ($update) {
                $rule = 'sometimes';
                $rule .= ($base === '') ? '|nullable' : '|'.$base;
            } else {
                $rule = $required ? 'required' : 'nullable';
                if ($base !== '') {
                    $rule .= '|'.$base;
                }
            }

            if (! empty($f['unique'])) {
                $rule .= '|unique:'.$this->plural().','.$f['name'];
            }

            $lines[] = "            '".$f['name']."' => '".$rule."',";
        }

        return implode(PHP_EOL, $lines);
    }

    protected function genRequests(): void
    {
        $class = $this->class();

        foreach (['Store', 'Update'] as $type) {
            $file = app_path('Http/Requests/'.$type.$class.'Request.php');
            if (file_exists($file)) {
                continue;
            }

            $content = <<<PHP
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {$type}{$class}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
@@RULES@@
        ];
    }
}
PHP;

            $content = strtr($content, [self::RULES => $this->rulesFor($type === 'Update')]);
            file_put_contents($file, $content.PHP_EOL);
        }

        $this->info('Form requests created.');
    }

    /* ------------------------------------------------------------------ */
    /* routes + controller                                                 */
    /* ------------------------------------------------------------------ */

    protected function genRoutes(): void
    {
        $dir = base_path('routes/modules');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = $dir.'/'.$this->plural().'.php';
        if (file_exists($file)) {
            $this->warn('Route file exists — skipping.');

            return;
        }

        $class = $this->class();
        $plural = $this->plural();

        $content = <<<PHP
<?php

use App\Http\Controllers\\{$class}Controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('{$plural}', {$class}Controller::class)->except('show');
    Route::get('{$plural}/data', [{$class}Controller::class, 'datatable'])->name('{$plural}.datatable');
});
PHP;

        file_put_contents($file, $content.PHP_EOL);
        $this->info('Routes created.');
    }

    protected function dtColumnsJson(): string
    {
        return json_encode($this->dtColumns());
    }

    protected function dtColumns(): array
    {
        $cols = [];
        foreach ($this->fields() as $f) {
            if (($f['datatable'] ?? true) === false) {
                continue;
            }
            if (in_array($f['type'], ['longText', 'text', 'json', 'foreignId'])) {
                continue;
            }
            $cols[] = [
                'label' => $f['label'] ?? Str::title(str_replace('_', ' ', $f['name'])),
                'data' => $f['name'],
            ];
        }

        return $cols;
    }

    protected function relationInitCode(): string
    {
        $code = '';
        foreach ($this->fields() as $f) {
            if (($f['type'] ?? '') !== 'foreignId' || empty($f['relation'])) {
                continue;
            }
            $model = Str::studly($f['relation']['model']);
            $display = $f['relation']['display'] ?? 'name';
            $code .= "    \$relations['".$f['name']."'] = \\App\\Models\\{$model}::pluck('{$display}', 'id');\n";
        }

        return $code === '' ? '' : rtrim($code);
    }

    protected function genController(): void
    {
        $class = $this->class();
        $file = app_path('Http/Controllers/'.$class.'Controller.php');

        if (file_exists($file)) {
            $this->warn('Controller exists — skipping.');

            return;
        }

        $plural = $this->plural();
        $single = $this->single();
        $relations = $this->relationInitCode();
        $columnsPhp = var_export($this->dtColumns(), true);

        $template = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store@@CLASS@@Request;
use App\Http\Requests\Update@@CLASS@@Request;
use App\Models\@@CLASS@@;
use App\Services\@@CLASS@@Service;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class @@CLASS@@Controller extends Controller
{
    public function __construct(protected @@CLASS@@Service $service)
    {
    }

    public function index()
    {
        $this->authorize('view-@@PLURAL@@');

        return view('@@PLURAL@@.index', ['columns' => @@COLUMNS_JSON@@]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-@@PLURAL@@');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (@@CLASS@@ $row) {
                return view('@@PLURAL@@.partials.actions', ['row' => $row])->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-@@PLURAL@@');
@@RELS_INIT@@
        return view('@@PLURAL@@.create', @@VIEW_RELS@@);
    }

    public function store(Store@@CLASS@@Request $request)
    {
        $this->authorize('create-@@PLURAL@@');

        $this->service->create($request->validated());

        return redirect()->route('@@PLURAL@@.index')->with('success', 'Created successfully.');
    }

    public function edit(@@CLASS@@ $@@SINGLE@@)
    {
        $this->authorize('update-@@PLURAL@@');
@@RELS_INIT@@
        return view('@@PLURAL@@.edit', ['@@SINGLE@@' => $@@SINGLE@@, 'relations' => $relations]);
    }

    public function update(Update@@CLASS@@Request $request, @@CLASS@@ $@@SINGLE@@)
    {
        $this->authorize('update-@@PLURAL@@');

        $this->service->update($@@SINGLE@@->id, $request->validated());

        return redirect()->route('@@PLURAL@@.index')->with('success', 'Updated successfully.');
    }

    public function destroy(@@CLASS@@ $@@SINGLE@@)
    {
        $this->authorize('delete-@@PLURAL@@');

        $this->service->delete($@@SINGLE@@->id);

        return redirect()->route('@@PLURAL@@.index')->with('success', 'Deleted successfully.');
    }
}
PHP;

$template = str_replace(
            ['@@PLURAL@@', '@@CLASS@@', '@@SINGLE@@', '@@COLUMNS_JSON@@', '@@RELS_INIT@@'],
            [$plural, $class, $single, $columnsPhp, $relations],
            $template
        );

        $relsView = $relations === '' ? '' : "['relations' => \$relations]";
        $template = str_replace('@@VIEW_RELS@@', $relsView, $template);

        file_put_contents($file, $template.PHP_EOL);
        $this->info('Controller created.');
    }

    /* ------------------------------------------------------------------ */
    /* views                                                               */
    /* ------------------------------------------------------------------ */

    protected function genViews(): void
    {
        $plural = $this->plural();
        $dir = resource_path('views/'.$plural);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (! is_dir($dir.'/partials')) {
            mkdir($dir.'/partials', 0777, true);
        }

        $this->writeIndex($dir, $plural);
        $this->writeCreateEdit($dir, $plural, 'create');
        $this->writeCreateEdit($dir, $plural, 'edit');
        $this->writeFormPartial($dir);
        $this->writeActions($dir);
        $this->info('Views created.');
    }

    protected function writeIndex($dir, $plural)
    {
        $label = $this->label();

        $template = <<<'BLADE'
@extends('layouts.app')

@section('title', '@@LABEL@@')

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">@@LABEL@@</h3>
        <div class="card-tools">
            @can('create-@@PLURAL@@')
                <a href="{{ route('@@PLURAL@@.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> New</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <table id="dt-@@PLURAL@@" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 40px">#</th>
                @foreach($columns as $col)
                    <th>{{ $col['label'] }}</th>
                @endforeach
                <th style="width: 120px">Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function () {
        var columns = {!! json_encode($columns) !!};
        var dtCols = [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }
        ];
        columns.forEach(function (c) {
            dtCols.push({ data: c.data, name: c.data });
        });
        dtCols.push({ data: 'actions', name: 'actions', orderable: false, searchable: false });

        $('#dt-@@PLURAL@@').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('@@PLURAL@@.datatable') }}',
            columns: dtCols,
            order: [[1, 'asc']]
        });
    });
</script>
@endpush
BLADE;

        $template = strtr($template, [
            '@@LABEL@@' => $label,
            '@@PLURAL@@' => $plural,
        ]);

        file_put_contents($dir.'/index.blade.php', $template.PHP_EOL);
    }

    protected function writeCreateEdit($dir, $plural, $kind)
    {
        $title = $kind === 'create'
            ? 'New '.$this->label()
            : 'Edit '.$this->label();

        $template = <<<'BLADE'
@extends('layouts.app')

@section('title', '@@TITLE@@')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">@@TITLE@@</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route(@@FORM_ACTION@@) }}">
                    @csrf
@@FORM_METHOD@@
                    @include('@@PLURAL@@.partials.form', ['form' => @@FORM_MODEL@@])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('@@PLURAL@@.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

        if ($kind === 'create') {
            $action = "'".$plural.".store'";
            $method = '';
            $model = 'null';
        } else {
            $action = "'".$plural.".update', \$".$this->single()."->id";
            $method = '                    @method("PATCH")';
            $model = '$'.$this->single();
        }

        $template = strtr($template, [
            '@@TITLE@@' => $title,
            '@@PLURAL@@' => $plural,
            '@@FORM_MODEL@@' => $model,
            '@@FORM_ACTION@@' => $action,
            '@@FORM_METHOD@@' => $method,
        ]);

        file_put_contents($dir.'/'.$kind.'.blade.php', $template.PHP_EOL);
    }

    protected function writeFormPartial($dir)
    {
        $template = <<<'BLADE'
<div class="row">
@@FIELDS@@
</div>
BLADE;

        $template = str_replace('@@FIELDS@@', rtrim($this->fieldsHtml()), $template);
        file_put_contents($dir.'/partials/form.blade.php', $template.PHP_EOL);
    }

    protected function fieldsHtml(): string
    {
        $html = '';
        foreach ($this->fields() as $f) {
            $name = $f['name'];
            $label = $f['label'] ?? Str::title(str_replace('_', ' ', $name));
            $reqSuffix = ! empty($f['required']) ? ' *' : '';
            $input = '';
            $valOld = "old('$name', \$form->$name ?? '')";
            $col = ($f['form'] ?? 'text') === 'textarea' ? 12 : ($f['full'] ?? false ? 12 : 6);

            switch ($f['form'] ?? $f['type']) {
                case 'textarea':
                    $input = "<textarea name=\"$name\" id=\"$name\" rows=\"3\" class=\"form-control @error('$name') is-invalid @enderror\">{{ $valOld }}</textarea>";
                    break;
                case 'select':
                case 'enum':
                    $options = '';
                    foreach (($f['options'] ?? []) as $o) {
                        $selected = "old('$name', \$form->$name ?? '') == '$o' ? 'selected' : ''";
                        $options .= "<option value=\"$o\" {{ $selected }}>$o</option>";
                    }
                    $input = "<select name=\"$name\" id=\"$name\" class=\"form-control @error('$name') is-invalid @enderror\"><option value=\"\">-- Select --</option>$options</select>";
                    break;
                case 'boolean':
                case 'switch':
                    $input = "<div class=\"form-check\"><input type=\"checkbox\" name=\"$name\" id=\"$name\" value=\"1\" class=\"form-check-input @error('$name') is-invalid @enderror\" {{ old('$name', \$form->$name ?? false) ? 'checked' : '' }}><label class=\"form-check-label\" for=\"$name\">$label</label><div class=\"invalid-feedback\">@error('$name') {{ \$message }} @enderror</div></div>";
                    $label = '';
                    break;
                case 'date':
                    $input = "<input type=\"date\" name=\"$name\" id=\"$name\" class=\"form-control\" value=\"{{ $valOld }}\">";
                    break;
                case 'dateTime':
                    $input = "<input type=\"datetime-local\" name=\"$name\" id=\"$name\" class=\"form-control\" value=\"{{ $valOld }}\">";
                    break;
                case 'number':
                case 'integer':
                    $step = isset($f['scale']) ? ' step="0.01"' : '';
                    $input = "<input type=\"number\"$step name=\"$name\" id=\"$name\" class=\"form-control\" value=\"{{ $valOld }}\">";
                    break;
                case 'email':
                    $input = "<input type=\"email\" name=\"$name\" id=\"$name\" class=\"form-control\" value=\"{{ $valOld }}\">";
                    break;
                case 'relation':
                    $input = $this->relationSelect($f);
                    break;
                default:
                    $input = "<input type=\"text\" name=\"$name\" id=\"$name\" class=\"form-control\" value=\"{{ $valOld }}\">";
            }

            $html .= "<div class=\"col-md-$col col-field\">\n";
            if ($label !== '') {
                $html .= "            <label for=\"$name\" class=\"form-label\">$label$reqSuffix</label>\n";
            }
            $html .= "            $input\n";
            $html .= "            @error('$name')\n";
            $html .= "                <span class=\"text-danger small\">{{ \$message }}</span>\n";
            $html .= "            @enderror\n";
            $html .= "        </div>\n";
        }

        return rtrim($html);
    }

    protected function relationSelect(array $f): string
    {
        $name = $f['name'];

        $tpl = <<<'HTML'
                        <select name="@@FIELD@@" id="@@FIELD@@" class="form-control @error('@@FIELD@@') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['@@FIELD@@'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('@@FIELD@@', $form->@@FIELD@@ ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
HTML;

        return str_replace('@@FIELD@@', $name, $tpl);
    }

    protected function writeActions($dir)
    {
        $plural = $this->plural();
        $single = $this->single();

        $template = <<<'BLADE'
<div class="btn-group" role="group">
    <a href="{{ route('@@PLURAL@@.edit', $row->id) }}" class="btn btn-xs btn-info" title="Edit"><i class="fas fa-pen"></i></a>
    <form method="POST" action="{{ route('@@PLURAL@@.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-xs btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
    </form>
</div>
BLADE;

        $template = str_replace(['@@PLURAL@@'], [$plural], $template);
        file_put_contents($dir.'/partials/actions.blade.php', $template.PHP_EOL);
    }

    /* ------------------------------------------------------------------ */
    /* sidebar + permissions                                               */
    /* ------------------------------------------------------------------ */

    protected function registerPermissions(): void
    {
        $configFile = config_path('permissions.php');
        if (! file_exists($configFile)) {
            file_put_contents($configFile, "<?php\n\nreturn [];\n");
        }

        $perms = require $configFile;
        $root = $this->permissionRoot();
        $group = $this->group();

        $perms[$group] = $perms[$group] ?? [];
        foreach (['view', 'create', 'update', 'delete'] as $action) {
            $perm = $action.'-'.$root;
            if (! in_array($perm, $perms[$group], true)) {
                $perms[$group][] = $perm;
            }
        }

        $content = "<?php\n\nreturn ".var_export($perms, true).";\n";
        file_put_contents($configFile, $content);
        $this->info('Permissions registered.');
    }

    protected function registerMenu(): void
    {
        $configFile = config_path('menu.php');
        if (! file_exists($configFile)) {
            file_put_contents($configFile, "<?php\n\nreturn [];\n");
        }

        $menu = require $configFile;
        $groupLabel = $this->group();
        $item = [
            'label' => $this->label(),
            'route' => $this->plural().'.index',
            'icon' => $this->icon(),
            'permission' => 'view-'.$this->permissionRoot(),
        ];

        $found = false;
        foreach ($menu as &$group) {
            if (($group['label'] ?? '') !== $groupLabel) {
                continue;
            }
            $group['icon'] = $group['icon'] ?? $this->groupIcon();
            $routes = array_column($group['items'] ?? [], 'route');
            if (! in_array($item['route'], $routes, true)) {
                $group['items'][] = $item;
            }
            $found = true;
            break;
        }
        unset($group);

        if (! $found) {
            $menu[] = [
                'label' => $groupLabel,
                'icon' => $this->groupIcon(),
                'items' => [$item],
            ];
        }

        $content = "<?php\n\nreturn ".var_export($menu, true).";\n";
        file_put_contents($configFile, $content);
        $this->info('Menu registered.');
    }
}
PHP;