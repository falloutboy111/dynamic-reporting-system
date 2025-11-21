<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <flux:heading size="lg">{{ __('Profile Information') }}</flux:heading>
        <flux:subheading>{{ __("Update your account's profile information and email address.") }}</flux:subheading>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <flux:input 
            wire:model="name" 
            label="{{ __('Name') }}" 
            type="text"
            required 
            autofocus 
            autocomplete="name"
        />

        <div>
            <flux:input 
                wire:model="email" 
                label="{{ __('Email') }}" 
                type="email"
                required 
                autocomplete="username"
            />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3">
                    <flux:text size="sm" class="text-zinc-600 dark:text-zinc-400">
                        {{ __('Your email address is unverified.') }}
                        
                        <flux:button 
                            wire:click.prevent="sendVerification" 
                            variant="ghost" 
                            size="sm"
                            class="underline"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </flux:button>
                    </flux:text>

                    @if (session('status') === 'verification-link-sent')
                        <flux:text size="sm" class="mt-2 text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </flux:text>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>

            <flux:text size="sm" class="text-green-600 dark:text-green-400" x-data="{ show: false }" 
                   x-show="show" x-transition
                   @profile-updated.window="show = true; setTimeout(() => show = false, 2000)"
                   style="display: none;">
                {{ __('Saved.') }}
            </flux:text>
        </div>
    </form>
</section>
