<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterOrganisation = '';
    public $showModal = false;
    public $editMode = false;

    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $organisation_id;
    public $role = 'user';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->editMode ? ',' . $this->userId : ''),
            'password' => $this->editMode ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'organisation_id' => $this->role === 'user' ? 'required|exists:organisations,id' : 'nullable',
            'role' => 'required|in:admin,user',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterOrganisation()
    {
        $this->resetPage();
    }

    public function updatedRole()
    {
        if ($this->role === 'admin') {
            $this->organisation_id = null;
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::with('organisation')->findOrFail($id);
        
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->organisation_id = $user->organisation_id;
        $this->role = $user->hasRole('admin') ? 'admin' : 'user';
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $user = User::findOrFail($this->userId);
            
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'organisation_id' => $this->role === 'admin' ? null : $this->organisation_id,
            ];

            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            $user->update($userData);
            
            // Update role
            $user->syncRoles([$this->role]);

            session()->flash('message', 'User updated successfully.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'organisation_id' => $this->role === 'admin' ? null : $this->organisation_id,
            ]);

            // Assign role
            $user->assignRole($this->role);

            session()->flash('message', 'User created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->organisation_id = null;
        $this->role = 'user';
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = User::with(['organisation', 'roles'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterOrganisation, function ($query) {
                $query->where('organisation_id', $this->filterOrganisation);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $organisations = Organisation::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'organisations' => $organisations,
        ]);
    }
}
