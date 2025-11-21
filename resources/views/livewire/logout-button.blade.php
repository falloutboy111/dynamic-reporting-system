<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <flux:menu.item 
        icon="arrow-right-start-on-rectangle" 
        wire:click="logout"
    >
        {{ __('Log Out') }}
    </flux:menu.item>
</div>

