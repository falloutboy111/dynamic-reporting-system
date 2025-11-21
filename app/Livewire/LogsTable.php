<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\XeroLog;
use Livewire\Component;
use Livewire\WithPagination;

class LogsTable extends Component
{
    use WithPagination;

    public $filterType = '';
    public $filterStatus = '';

    public function render()
    {
        $query = XeroLog::with('organisation')
            ->latest();

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.logs-table', [
            'logs' => $query->paginate(15),
        ]);
    }

    public function clearFilters()
    {
        $this->filterType = '';
        $this->filterStatus = '';
        $this->resetPage();
    }
}

