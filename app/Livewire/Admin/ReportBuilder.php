<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Organisation;
use App\Models\Report;
use App\Services\DatabaseConnectionManager;
use App\Services\DatabaseSchemaDiscovery;
use App\Services\SqlQueryValidator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ReportBuilder extends Component
{
    use WithPagination;

    // Builder state
    public $viewMode = 'list'; // list or builder
    public $selectedOrganisation = null;
    public $schema = [];
    public $schemaLoading = false;

    // Report form
    public $reportId = null;
    public $editMode = false;
    public $name = '';
    public $description = '';
    public $sql_query = '';
    public $visualization_type = 'table';
    public $chart_config = [];

    // Chart configuration
    public $chart_type = 'bar';
    public $chart_label_column = '';
    public $chart_data_column = '';

    // Query preview
    public $previewResults = [];
    public $previewError = null;
    public $showPreview = false;

    // Search
    public $search = '';
    public $filterOrganisation = '';

    protected $rules = [
        'selectedOrganisation' => 'required|exists:organisations,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'sql_query' => 'required|string',
        'visualization_type' => 'required|in:table,chart',
        'chart_type' => 'required_if:visualization_type,chart|in:line,bar,pie,doughnut',
        'chart_label_column' => 'required_if:visualization_type,chart|string',
        'chart_data_column' => 'required_if:visualization_type,chart|string',
    ];

    public function mount()
    {
        $this->chart_config = [
            'type' => 'bar',
            'label_column' => '',
            'data_column' => '',
        ];
    }

    public function updatedSelectedOrganisation()
    {
        if ($this->selectedOrganisation) {
            $this->loadSchema();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterOrganisation()
    {
        $this->resetPage();
    }

    public function showBuilder()
    {
        $this->viewMode = 'builder';
        $this->resetBuilder();
    }

    public function showList()
    {
        $this->viewMode = 'list';
        $this->resetBuilder();
    }

    public function loadSchema()
    {
        $this->schemaLoading = true;
        
        try {
            $organisation = Organisation::findOrFail($this->selectedOrganisation);
            $schemaDiscovery = app(DatabaseSchemaDiscovery::class);
            $this->schema = $schemaDiscovery->getSchema($organisation);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load database schema: ' . $e->getMessage());
            $this->schema = [];
        } finally {
            $this->schemaLoading = false;
        }
    }

    public function refreshSchema()
    {
        if ($this->selectedOrganisation) {
            $organisation = Organisation::findOrFail($this->selectedOrganisation);
            $schemaDiscovery = app(DatabaseSchemaDiscovery::class);
            $schemaDiscovery->clearCache($organisation);
            $this->loadSchema();
            session()->flash('message', 'Schema refreshed successfully.');
        }
    }

    public function validateAndPreview()
    {
        $this->validate([
            'selectedOrganisation' => 'required|exists:organisations,id',
            'sql_query' => 'required|string',
        ]);

        $validator = app(SqlQueryValidator::class);
        $validationResult = $validator->validate($this->sql_query);

        if (!$validationResult['valid']) {
            $this->previewError = $validationResult['error'];
            $this->previewResults = [];
            $this->showPreview = true;
            return;
        }

        try {
            $organisation = Organisation::findOrFail($this->selectedOrganisation);
            $connectionManager = app(DatabaseConnectionManager::class);
            
            $connectionManager->createConnection($organisation);
            $connectionName = $connectionManager->getConnectionName($organisation);

            // Execute query with limit for preview
            $query = $this->sql_query;
            if (stripos($query, 'LIMIT') === false) {
                $query .= ' LIMIT 10';
            }

            $results = DB::connection($connectionName)->select($query);
            $this->previewResults = json_decode(json_encode($results), true);
            $this->previewError = null;
            $this->showPreview = true;

            $connectionManager->closeConnection($organisation);
        } catch (\Exception $e) {
            $this->previewError = 'Error executing query: ' . $e->getMessage();
            $this->previewResults = [];
            $this->showPreview = true;
        }
    }

    public function closePreview()
    {
        $this->showPreview = false;
    }

    public function saveReport()
    {
        $this->validate();

        // Additional validation for SQL
        $validator = app(SqlQueryValidator::class);
        $validationResult = $validator->validate($this->sql_query);

        if (!$validationResult['valid']) {
            $this->addError('sql_query', $validationResult['error']);
            return;
        }

        // Prepare chart config
        $chartConfig = null;
        if ($this->visualization_type === 'chart') {
            $chartConfig = [
                'type' => $this->chart_type,
                'label_column' => $this->chart_label_column,
                'data_column' => $this->chart_data_column,
            ];
        }

        if ($this->editMode) {
            $report = Report::findOrFail($this->reportId);
            $report->update([
                'organisation_id' => $this->selectedOrganisation,
                'name' => $this->name,
                'description' => $this->description,
                'sql_query' => $this->sql_query,
                'visualization_type' => $this->visualization_type,
                'chart_config' => $chartConfig,
            ]);
            session()->flash('message', 'Report updated successfully.');
        } else {
            Report::create([
                'organisation_id' => $this->selectedOrganisation,
                'name' => $this->name,
                'description' => $this->description,
                'sql_query' => $this->sql_query,
                'visualization_type' => $this->visualization_type,
                'chart_config' => $chartConfig,
                'created_by' => auth()->id(),
            ]);
            session()->flash('message', 'Report created successfully.');
        }

        $this->showList();
    }

    public function editReport($id)
    {
        $report = Report::with('organisation')->findOrFail($id);
        
        $this->reportId = $report->id;
        $this->selectedOrganisation = $report->organisation_id;
        $this->name = $report->name;
        $this->description = $report->description;
        $this->sql_query = $report->sql_query;
        $this->visualization_type = $report->visualization_type;
        
        if ($report->chart_config) {
            $this->chart_type = $report->chart_config['type'] ?? 'bar';
            $this->chart_label_column = $report->chart_config['label_column'] ?? '';
            $this->chart_data_column = $report->chart_config['data_column'] ?? '';
        }
        
        $this->editMode = true;
        $this->viewMode = 'builder';
        $this->loadSchema();
    }

    public function deleteReport($id)
    {
        Report::findOrFail($id)->delete();
        session()->flash('message', 'Report deleted successfully.');
    }

    public function toggleReportStatus($id)
    {
        $report = Report::findOrFail($id);
        $report->is_active = !$report->is_active;
        $report->save();
        session()->flash('message', 'Report status updated.');
    }

    private function resetBuilder()
    {
        $this->reportId = null;
        $this->editMode = false;
        $this->selectedOrganisation = null;
        $this->schema = [];
        $this->name = '';
        $this->description = '';
        $this->sql_query = '';
        $this->visualization_type = 'table';
        $this->chart_type = 'bar';
        $this->chart_label_column = '';
        $this->chart_data_column = '';
        $this->previewResults = [];
        $this->previewError = null;
        $this->showPreview = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        if ($this->viewMode === 'list') {
            $reports = Report::with(['organisation', 'creator'])
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->when($this->filterOrganisation, function ($query) {
                    $query->where('organisation_id', $this->filterOrganisation);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $organisations = Organisation::where('is_active', true)->orderBy('name')->get();

            return view('livewire.admin.report-builder', [
                'reports' => $reports,
                'organisations' => $organisations,
            ]);
        }

        $organisations = Organisation::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.report-builder', [
            'organisations' => $organisations,
        ]);
    }
}
