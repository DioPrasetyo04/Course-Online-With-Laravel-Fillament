<nav id="nav-guest" class="flex w-full bg-white border-b border-obito-grey">
    <div class="flex w-[1280px] px-[75px] py-5 items-center justify-between mx-auto">
        <div class="flex items-center gap-[50px]">
            <a href={{ route('front.index') }} class="flex shrink-0">
                <img src="{{ asset('assets/images/logos/logo.svg') }}" class="flex shrink-0" alt="logo">
            </a>
            <ul class="flex items-center gap-10">
                <li
                    class="{{ request()->routeIs('front.index') ? 'font-semibold text-orange-500 hover:text-blue-500' : 'font-normal text-black hover:text-orange-500 hover:font-semibold' }} transition-all duration-300 font-semibold">
                    <a href={{ route('front.index') }}>Home</a>
                </li>
                <li
                    class="{{ request()->routeIs('front.pricing') ? 'font-semibold text-orange-500 hover:text-blue-500' : 'font-normal text-black hover:text-orange-500 hover:font-semibold' }} transition-all duration-300 font-semibold">
                    <a href={{ route('front.pricing') }}>Pricing</a>
                </li>
                <li
                    class="{{ request()->routeIs('front.features') ? 'font-semibold text-orange-500 hover:text-blue-500' : 'font-normal text-black hover:text-orange-500 hover:font-semibold' }} transition-all duration-300 font-semibold">
                    <a href="{{ route('dashboard') }}">Course</a>
                </li>
                <li
                    class="{{ request()->routeIs('front.testimonials') ? 'font-semibold text-orange-500 hover:text-blue-500' : 'font-normal text-black hover:text-orange-500 hover:font-semibold' }} transition-all duration-300 font-semibold">
                    <a href="#">Testimonials</a>
                </li>
            </ul>
        </div>
        <div class="flex items-center gap-5 justify-end">
            <a href="#" class="flex shrink-0">
                <img src="{{ asset('assets/images/icons/device-message.svg') }}" class="flex shrink-0" alt="icon">
            </a>
            <div class="h-[50px] flex shrink-0 bg-obito-grey w-px"></div>
            <div class="flex items-center gap-3">
                @auth
                    <div id="profile-dropdown" class="relative flex items-center gap-[14px]">
                        <div class="flex shrink-0 w-[50px] h-[50px] rounded-full overflow-hidden bg-obito-grey">
                            <img src={{ Storage::url(auth()->user()->photo) }} class="w-full h-full object-cover"
                                alt="photo">
                        </div>
                        <div>
                            <p class="font-semibold text-lg">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-obito-text-secondary">{{ auth()->user()->occupation }}</p>
                        </div>
                        <button id="dropdown-opener" class="flex shrink-0 w-6 h-6">
                            <img src="{{ asset('assets/images/icons/arrow-circle-down.svg') }}" class="w-6 h-6"
                                alt="icon">
                        </button>
                        <div id="dropdown"
                            class="absolute top-full right-0 mt-[7px] w-[170px] h-fit bg-white rounded-xl border border-obito-grey py-4 px-5 shadow-[0px_10px_30px_0px_#B8B8B840] z-10 hidden">
                            <ul class="flex flex-col gap-[14px]">
                                <li class="hover:text-obito-green transition-all duration-300">
                                    <a href="{{ route('dashboard') }}">My Courses</a>
                                </li>
                                <li class="hover:text-obito-green transition-all duration-300">
                                    <a href="#">Certificates</a>
                                </li>
                                <li class="hover:text-obito-green transition-all duration-300">
                                    <a href="{{ route('dashboard.subscription') }}">Subscriptions</a>
                                </li>
                                <li class="hover:text-obito-green transition-all duration-300">
                                    <a href="#">Settings</a>
                                </li>
                                <li class="hover:text-obito-green transition-all duration-300">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <a href="logout"
                                            onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endauth
                @guest
                    <a href={{ route('register') }}
                        class="rounded-full border border-obito-grey py-3 px-5 gap-[10px] bg-white hover:border-obito-green transition-all duration-300">
                        <span class="font-semibold">Sign Up</span>
                    </a>
                    <a href={{ route('login') }}
                        class="rounded-full py-3 px-5 gap-[10px] bg-obito-green hover:drop-shadow-effect transition-all duration-300">
                        <span class="font-semibold text-white">My Account</span>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>
