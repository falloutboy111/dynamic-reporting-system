<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Organisation;
use App\Services\DatabaseConnectionManager;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class OrganisationManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showTestModal = false;
    public $editMode = false;

    public $organisationId;
    public $name;
    public $database_host;
    public $database_name;
    public $database_username;
    public $database_password;
    public $database_port = 3306;
    public $is_active = true;

    public $testConnectionResult = null;
    public $testConnectionMessage = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'database_host' => 'nullable|string|max:255',
        'database_name' => 'nullable|string|max:255',
        'database_username' => 'nullable|string|max:255',
        'database_password' => 'nullable|string|max:255',
        'database_port' => 'nullable|integer|min:1|max:65535',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $organisation = Organisation::findOrFail($id);
        
        $this->organisationId = $organisation->id;
        $this->name = $organisation->name;
        $this->database_host = $organisation->database_host;
        $this->database_name = $organisation->database_name;
        $this->database_username = $organisation->database_username;
        $this->database_password = $organisation->database_password;
        $this->database_port = $organisation->database_port;
        $this->is_active = $organisation->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $organisation = Organisation::findOrFail($this->organisationId);
            $organisation->update([
                'name' => $this->name,
                'database_host' => $this->database_host,
                'database_name' => $this->database_name,
                'database_username' => $this->database_username,
                'database_password' => $this->database_password,
                'database_port' => (int) $this->database_port,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Organisation updated successfully.');
        } else {
            Organisation::create([
                'name' => $this->name,
                'database_host' => $this->database_host,
                'database_name' => $this->database_name,
                'database_username' => $this->database_username,
                'database_password' => $this->database_password,
                'database_port' => (int) $this->database_port,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Organisation created successfully.');
        }

        $this->closeModal();
    }

    public function testConnection()
    {
        $this->validate([
            'database_host' => 'required|string',
            'database_name' => 'required|string',
            'database_username' => 'required|string',
            'database_password' => 'required|string',
            'database_port' => 'required|integer',
        ]);

        $connectionManager = app(DatabaseConnectionManager::class);
        
        $result = $connectionManager->testConnection(
            $this->database_host,
            $this->database_name,
            $this->database_username,
            $this->database_password,
            (int) $this->database_port
        );

        $this->testConnectionResult = $result['success'];
        $this->testConnectionMessage = $result['message'];
        $this->showTestModal = true;
    }

    public function closeTestModal()
    {
        $this->showTestModal = false;
        $this->testConnectionResult = null;
        $this->testConnectionMessage = null;
    }

    public function delete($id)
    {
        Organisation::findOrFail($id)->delete();
        session()->flash('message', 'Organisation deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $organisation = Organisation::findOrFail($id);
        $organisation->is_active = !$organisation->is_active;
        $organisation->save();
        
        session()->flash('message', 'Organisation status updated.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->organisationId = null;
        $this->name = '';
        $this->database_host = '';
        $this->database_name = '';
        $this->database_username = '';
        $this->database_password = '';
        $this->database_port = 3306;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $organisations = Organisation::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.organisation-management', [
            'organisations' => $organisations,
        ]);
    }
}
