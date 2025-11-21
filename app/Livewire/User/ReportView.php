<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Models\Report;
use App\Services\DatabaseConnectionManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.user')]
class ReportView extends Component
{
    public $reportId;
    public $report;
    public $results = [];
    public $error = null;
    public $loading = false;

    // Filters
    public $startDate = null;
    public $endDate = null;

    public function mount($id)
    {
        $this->reportId = $id;
        $this->loadReport();
    }

    public function loadReport()
    {
        $user = auth()->user();
        
        // Ensure user can only view reports from their organization
        $this->report = Report::where('id', $this->reportId)
            ->where('organisation_id', $user->organisation_id)
            ->where('is_active', true)
            ->with('organisation')
            ->firstOrFail();

        $this->executeReport();
    }

    public function executeReport()
    {
        $this->loading = true;
        $this->error = null;
        $this->results = [];

        try {
            $connectionManager = app(DatabaseConnectionManager::class);
            $connectionManager->createConnection($this->report->organisation);
            $connectionName = $connectionManager->getConnectionName($this->report->organisation);

            // Execute the query with timeout
            DB::connection($connectionName)->getPdo()->setAttribute(\PDO::ATTR_TIMEOUT, 30);
            
            $results = DB::connection($connectionName)->select($this->report->sql_query);
            $this->results = json_decode(json_encode($results), true);

            // Limit results to 10,000 rows
            if (count($this->results) > 10000) {
                $this->results = array_slice($this->results, 0, 10000);
                $this->error = 'Results limited to 10,000 rows. Please refine your query for more specific data.';
            }

            $connectionManager->closeConnection($this->report->organisation);
        } catch (\Exception $e) {
            $this->error = 'Error executing report: ' . $e->getMessage();
            $this->results = [];
        } finally {
            $this->loading = false;
        }
    }

    public function refresh()
    {
        $this->executeReport();
    }

    public function exportCsv()
    {
        if (empty($this->results)) {
            session()->flash('error', 'No data to export.');
            return;
        }

        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, array_keys($this->results[0]));
            
            // Add data
            foreach ($this->results as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        }, $this->report->name . '_' . now()->format('Y-m-d_His') . '.csv');
    }

    public function exportExcel()
    {
        session()->flash('message', 'Excel export requires additional package. CSV export is available.');
    }

    public function exportPdf()
    {
        session()->flash('message', 'PDF export requires additional package. CSV export is available.');
    }

    public function render()
    {
        return view('livewire.user.report-view');
    }
}
