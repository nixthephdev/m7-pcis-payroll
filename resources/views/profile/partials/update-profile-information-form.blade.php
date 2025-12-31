<section>
    <header class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                {{ __('Profile Information') }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Picture -->
        <div>
            <x-input-label for="avatar" :value="__('Profile Picture')" class="font-bold text-gray-700" />
            
            <div class="flex items-center gap-6 mt-3">
                <div class="relative group">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                    @else
                        <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-2xl border-4 border-white shadow-md">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute inset-0 rounded-full border-2 border-gray-100 pointer-events-none"></div>
                </div>
                
                <div class="flex-1">
                    <input id="avatar" name="avatar" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer transition" />
                    <p class="text-xs text-gray-400 mt-2">JPG, PNG or GIF (Max 1MB)</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="font-bold text-gray-700" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="font-bold text-gray-700" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                    <p class="text-sm text-yellow-800">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-sm text-yellow-900 hover:text-yellow-700 font-bold ml-1">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 pt-4">
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 px-6 py-2.5 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                {{ __('Save Changes') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600 font-bold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>