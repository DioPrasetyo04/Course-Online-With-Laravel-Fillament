@extends('front_courses.layouts.app')
@section('title', 'Login - Course LMS Obito')
@section('content')
        <x-nav-guest />
        <main class="relative flex flex-1 h-full">
            <section class="flex flex-1 items-center py-5 px-5 pl-[calc(((100%-1280px)/2)+75px)]">
                <form method="POST" action="{{ route('login.store') }}" class="flex flex-col h-fit w-[510px] shrink-0 rounded-[20px] border border-obito-grey p-5 gap-5 bg-white">
                @csrf
                    <h1 class="font-bold text-[22px] leading-[33px] mb-5">Welcome Back, <br>Let’s Upgrade Skills</h1>
                    <div class="flex flex-col gap-2">
                        <p>Email Address</p>
                        <label class="relative group">
                            <input type="email" name="email" class="appearance-none outline-none w-full rounded-full border border-obito-grey py-[14px] px-5 pl-12 font-semibold placeholder:font-normal placeholder:text-obito-text-secondary group-focus-within:border-obito-green transition-all duration-300" placeholder="Type your valid email address">
                            <img src="{{ asset('assets/images/icons/sms.svg') }}" class="absolute size-5 flex shrink-0 transform -translate-y-1/2 top-1/2 left-5" alt="icon">
                        </label>
                        <x-input-error :messages="$errors->get('email')" class="text-red-600 mt-2" />
                    </div>
                    <div class="flex flex-col gap-3">
                        <p>Password</p>
                        <label class="relative group">
                            <input type="password" name="password" class="appearance-none outline-none w-full rounded-full border border-obito-grey py-[14px] px-5 pl-12 font-semibold placeholder:font-normal placeholder:text-obito-text-secondary group-focus-within:border-obito-green transition-all duration-300" placeholder="Type your password">
                            <img src="{{ asset('assets/images/icons/shield-security.svg') }}" class="absolute size-5 flex shrink-0 transform -translate-y-1/2 top-1/2 left-5" alt="icon">
                        </label>
                        <x-input-error :messages="$errors->get('password')" class="text-red-600 mt-2" />
                        <a href="#" class="text-sm text-obito-green hover:underline">Forgot My Password</a>
                    </div>
                    <button type="submit" class="flex items-center justify-center gap-[10px] rounded-full py-[14px] px-5 bg-obito-green hover:drop-shadow-effect transition-all duration-300">
                        <span class="font-semibold text-white">Sign In to My Account</span>
                    </button>
                </form>
            </section>
            <div class="relative flex w-1/2 shrink-0">
                <div id="background-banner" class="absolute flex w-full h-full overflow-hidden">
                    <img src="{{ asset('assets/images/backgrounds/banner-subscription.png') }}" class="w-full h-full object-cover" alt="banner">
                </div>
            </div>
        </main>
@endsection

@push('after-scripts')
    {{-- penempatan script yang akan diteruskan akan menggunakan push sebagai turunan --}}
    <script src="{{ asset('js/dropdown-navbar.js') }}"></script>
@endpush