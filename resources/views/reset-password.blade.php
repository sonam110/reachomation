
   <link rel="stylesheet" href="{{asset('css/views/reset-password.css')}}">
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{  url('/') }}">
                     <img src="{{asset('images/reachomation_logo_black.png')}}" class="img-fluid mt-1" width="180"
                        alt="logo">
                  </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        @include('sections.message')
        <form method="POST" action="{{ url('passwordReset') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Reset Password') }}
                </x-button>
            </div>
            <div class="d-grid mb-2"><a type="button" class="btn btn-outline-dark rounded-pill shadow-none" href="{{ route('login') }}">Login</a></div>
        </form>
    </x-auth-card>
</x-guest-layout>
