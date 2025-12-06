@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-common">
                <div class="px-6 py-4 border-b font-semibold">{{ __('Login') }}</div>

                <div class="p-6">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                            <input id="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
                            @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4 flex items-center">
                            <input class="form-checkbox h-4 w-4 text-brand-900" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="ml-2 text-sm text-gray-700" for="remember">{{ __('Remember Me') }}</label>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="btn-primary">{{ __('Login') }}</button>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
