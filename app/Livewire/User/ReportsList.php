<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.user')]
class ReportsList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        
        if (!$user->organisation_id) {
            return view('livewire.user.reports-list', [
                'reports' => collect([]),
            ]);
        }

        $reports = Report::where('organisation_id', $user->organisation_id)
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.user.reports-list', [
            'reports' => $reports,
        ]);
    }
}
