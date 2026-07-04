@extends('layouts.authTemplate')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-lg p-8">

        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold">
                Register
            </h1>

        </div>

        @if(session('error'))
            <div class="mb-4 p-3 rounded-xl bg-red-100 text-red-600">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-red-100 text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            action="{{ route('register.process') }}"
            method="POST">

            @csrf

            <!-- Email -->

            <div class="mb-4">

                <label class="block text-sm font-medium mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Username / Email"
                    required
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-500">

            </div>

            <!-- Password -->

            <div class="mb-4">

                <label class="block text-sm font-medium mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-500">

            </div>
            
            <!-- Confirm Password -->
    
            <div class="mb-4">

                <label class="block text-sm font-medium mb-2">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    required
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-500">

            </div>

            <!-- Submit -->

            <button
                type="submit"
                class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold py-3 rounded-2xl">

                Register

            </button>

        </form>

        <!-- Divider -->

        <div class="flex items-center my-6">

            <div class="flex-1 border-t"></div>

            <span class="px-4 text-gray-400 text-sm">
                Or
            </span>

            <div class="flex-1 border-t"></div>

        </div>

        <!-- Google Register -->

        <button
            type="button"
            class="w-full border border-gray-300 py-3 rounded-2xl font-medium hover:bg-gray-50">

            Sign In With Google

        </button>

        <!-- Register -->

        <div class="text-center mt-6">

            <span class="text-gray-500">
                Already have account?
            </span>

            <a
                href="{{ route('login') }}"
                class="text-violet-600 font-semibold hover:underline">

                Sign In

            </a>

        </div>

    </div>

</div>

@endsection