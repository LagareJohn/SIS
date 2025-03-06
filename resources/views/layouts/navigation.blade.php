<nav x-data="{ open: false }" class="fixed top-0 left-0 h-full bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300" :class="{'translate-x-0': open, '-translate-x-full': !open}">
    <!-- App Drawer Header -->
    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-12 w-auto" />
                </a>
                <span class="ml-3 text-xl font-semibold dark:text-white">BukSU - SIS</span>
            </div>
            <!-- Close Button -->
            <button @click="open = false" class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Drawer Content -->
    <div class="h-full overflow-y-auto">
        <!-- Navigation Links -->
        <div class="px-4 py-2">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="block py-2 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 mb-1">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('Dashboard') }}
                </div>
            </x-nav-link>

            <!-- User Profile Section -->
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="px-4 py-2">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <x-nav-link :href="route('profile.edit')" 
                    class="block py-2 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 mb-1">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('Profile') }}
                    </div>
                </x-nav-link>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="block py-2 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-400">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Log Out') }}
                        </div>
                    </x-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Hamburger Menu Button (Fixed to top) -->
<div class="fixed top-4 left-4 z-50">
    <button @click="open = true" class="p-2 rounded-md bg-white dark:bg-gray-800 shadow-lg">
        <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<!-- Overlay -->
<div x-show="open" 
    @click="open = false" 
    class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
</div>
