<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\JournalLine;
use App\Models\Organisation;
use App\Models\XeroLog;
use Livewire\Component;

class Reports extends Component
{
    public $selectedOrganisation = '';
    public $reportType = 'logs';

    public function render()
    {
        $organisations = Organisation::all();
        $logsSummary = null;
        $journalLines = collect();

        if ($this->reportType === 'logs') {
            $query = XeroLog::query();
            if ($this->selectedOrganisation) {
                $query->where('organisation_id', $this->selectedOrganisation);
            }

            $logsSummary = [
                'total' => $query->count(),
                'success' => (clone $query)->where('status', 'success')->count(),
                'failed' => (clone $query)->where('status', 'failed')->count(),
                'warning' => (clone $query)->where('status', 'warning')->count(),
            ];
        } else {
            $query = JournalLine::with(['journal.organisation']);
            if ($this->selectedOrganisation) {
                $query->whereHas('journal', function ($q) {
                    $q->where('organisation_id', $this->selectedOrganisation);
                });
            }

            $journalLines = $query->get()
                ->groupBy(function ($line) {
                    return $line->journal->organisation->name ?? 'Unknown';
                })
                ->map(function ($lines, $orgName) {
                    return [
                        'organisation' => $orgName,
                        'total_lines' => $lines->count(),
                        'total_net' => $lines->sum(fn($l) => (float) $l->net_amount),
                        'total_gross' => $lines->sum(fn($l) => (float) $l->gross_amount),
                        'total_tax' => $lines->sum(fn($l) => (float) $l->tax_amount),
                    ];
                });
        }

        return view('livewire.reports', [
            'organisations' => $organisations,
            'logsSummary' => $logsSummary,
            'journalLines' => $journalLines,
        ]);
    }
}

