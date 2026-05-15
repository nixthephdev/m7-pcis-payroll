<section>
    <header class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-slate-700 pb-4">
        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg text-indigo-600 dark:text-indigo-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ __('Profile Information') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Picture (display only — managed by HR/Admin) -->
        <div class="flex items-center gap-4">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover border-4 border-white dark:border-slate-700 shadow-md">
            @else
                <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-2xl border-4 border-white dark:border-slate-700 shadow-md">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            <p class="text-xs text-gray-400">Profile photo is managed by HR/Admin.</p>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="font-bold text-gray-700 dark:text-gray-300" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 transition" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="font-bold text-gray-700 dark:text-gray-300" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 transition" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <p class="text-sm text-yellow-800 dark:text-yellow-500">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-sm text-yellow-900 dark:text-yellow-400 hover:text-yellow-700 font-bold ml-1">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 pt-4">
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 px-6 py-2.5 rounded-lg shadow-md transition transform hover:-translate-y-0.5 border-none">
                {{ __('Save Changes') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>