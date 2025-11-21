<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <flux:input 
            label="Name" 
            type="text" 
            wire:model="name"
            :error="$errors->first('name')"
            autofocus
            required
            autocomplete="name" />

        <!-- Email Address -->
        <flux:input 
            label="Email" 
            type="email" 
            wire:model="email"
            :error="$errors->first('email')"
            required
            autocomplete="username" />

        <!-- Password -->
        <flux:input 
            label="Password" 
            type="password" 
            wire:model="password"
            :error="$errors->first('password')"
            required
            autocomplete="new-password" />

        <!-- Confirm Password -->
        <flux:input 
            label="Confirm Password" 
            type="password" 
            wire:model="password_confirmation"
            :error="$errors->first('password_confirmation')"
            required
            autocomplete="new-password" />

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <flux:button type="submit" class="ms-4" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="register">{{ __('Register') }}</span>
                <span wire:loading wire:target="register">{{ __('Registering...') }}</span>
            </flux:button>
        </div>
    </form>
</div>
