<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Organisation;
use Livewire\Component;
use Livewire\WithPagination;

class OrganisationsTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.organisations-table', [
            'organisations' => Organisation::latest('last_sync')
                ->paginate(10),
        ]);
    }
}

