@extends('layout.app')

@section('content')
<div class="w-full">

    <div data-guest-only>
        <h1 class="text-xl">Welcome guest!</h1>
        <a href="{{ route('login') }}" class="mr-4 text-blue-700">Login</a>
        <a href="{{ route('register') }}" class="text-green-700">Sign Up</a>
    </div>

    <div data-auth-only>
        <h1 class="text-xl text-green-700">Welcome back!</h1>
        {{-- <button data-logout-button class="text-red-500 underline ml-4">Logout</button> --}}
    </div>

</div>
@endsection
