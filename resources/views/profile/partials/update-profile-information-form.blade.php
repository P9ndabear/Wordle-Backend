<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Avatar -->
        <div>
            <label for="avatar" class="block font-medium text-sm text-gray-700">{{ __('Avatar') }}</label>
            <div class="mt-2 flex items-center gap-x-3">
                <img class="h-16 w-16 rounded-full" src="{{ asset('storage/' . ($user->avatar ?? 'avatars/default.png')) }}" alt="Current avatar">
                <input id="avatar" name="avatar" type="file" class="mt-1 block w-full" />
            </div>
            @error('avatar')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Account Details -->
        <div class="mt-4">
            <h3 class="text-md font-medium text-gray-900">{{ __('Account Details') }}</h3>
            <div class="mt-2 space-y-2 text-sm text-gray-600">
                <p><strong>{{ __('Registration Date') }}:</strong> {{ $user->created_at->format('F j, Y, g:i a') }}</p>
                @if ($user->last_login_at)
                    <p><strong>{{ __('Last Login') }}:</strong> {{ $user->last_login_at->format('F j, Y, g:i a') }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <hr class="my-6">
    <form method="POST" action="{{ route('preferences.update') }}" class="space-y-4">
        @csrf
        <h3 class="text-md font-medium text-gray-900">Voorkeuren</h3>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="privacy_profile" value="private" {{ $user->getPreference('privacy_profile') == 'private' ? 'checked' : '' }}>
                <span class="ml-2">Maak mijn profiel privé</span>
            </label>
        </div>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Opslaan</button>
        @if (session('status') === 'preferences-updated')
            <p class="text-sm text-gray-600 mt-2">Voorkeuren opgeslagen.</p>
        @endif
    </form>
</section>
