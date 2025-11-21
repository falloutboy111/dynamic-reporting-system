<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <flux:input 
            label="Email" 
            type="email" 
            wire:model="form.email"
            :error="$errors->first('form.email')"
            autofocus
            required
            autocomplete="username" />

        <!-- Password -->
        <flux:input 
            label="Password" 
            type="password" 
            wire:model="form.password"
            :error="$errors->first('form.password')"
            required
            autocomplete="current-password" />

        <!-- Remember Me -->
        <div class="block mt-4">
            <flux:checkbox wire:model="form.remember" id="remember" name="remember">
                {{ __('Remember me') }}
            </flux:checkbox>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <flux:button type="submit" class="ms-3" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="login">{{ __('Log in') }}</span>
                <span wire:loading wire:target="login">{{ __('Logging in...') }}</span>
            </flux:button>
        </div>
    </form>
</div>
