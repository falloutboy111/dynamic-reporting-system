<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UsersManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingUserId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'user';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:user,admin',
    ];

    public function openModal($userId = null)
    {
        $this->resetForm();
        if ($userId) {
            $user = User::findOrFail($userId);
            $this->editingUserId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->roles->first()?->name ?? 'user';
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'user';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $user->name = $this->name;
            $user->email = $this->email;
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
        }

        // Assign role
        $role = Role::where('name', $this->role)->first();
        if ($role) {
            $user->syncRoles([$role]);
        }

        $this->closeModal();
        session()->flash('message', $this->editingUserId ? 'User updated successfully.' : 'User created successfully.');
    }

    public function delete($userId)
    {
        if ($userId === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        User::findOrFail($userId)->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    public function render()
    {
        return view('livewire.users-management', [
            'users' => User::with('roles')
                ->latest()
                ->paginate(10),
            'roles' => Role::all(),
        ]);
    }
}

