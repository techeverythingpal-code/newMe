<nav x-data="{ open: false }" class="bg-gradient-to-l from-indigo-600 to-purple-600 shadow-lg sticky top-0 z-50" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-lg text-white">
                    <span class="text-2xl">📋</span>
                    <span class="hidden sm:inline">نظام تقارير المعلمين</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:gap-1 sm:me-8 sm:ms-10">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard')
                                ? 'bg-white/20 text-white'
                                : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                        <span>🏠</span> لوحة التحكم
                    </a>

                    @if(Auth::guard('admin')->check())
                        <a href="{{ route('directorates.index') }}"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ request()->routeIs('directorates.*')
                                    ? 'bg-white/20 text-white'
                                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            <span>🏢</span> المديريات
                        </a>
                        <a href="{{ route('schools.index') }}"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ request()->routeIs('schools.*')
                                    ? 'bg-white/20 text-white'
                                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            <span>🏫</span> المدارس
                        </a>
                        <a href="{{ route('supervisors.index') }}"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ request()->routeIs('supervisors.*')
                                    ? 'bg-white/20 text-white'
                                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            <span>👤</span> المشرفون
                        </a>
                        
                        <a href="{{ route('teacher-grades.sheet') }}"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ request()->routeIs('teacher-grades.sheet')
                                    ? 'bg-white/20 text-white'
                                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            <span>📊</span> جدول الدرجات
                        </a>
                    @else
                        
                        <a href="{{ route('teacher-grades.sheet') }}"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ request()->routeIs('teacher-grades.sheet')
                                    ? 'bg-white/20 text-white'
                                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            <span>📊</span> جدول الدرجات
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition">
                            <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-sm">
                                👋
                            </span>
                            <span>
                                @if(Auth::guard('admin')->check())
                                    {{ Auth::guard('admin')->user()->name }}
                                @else
                                    {{ Auth::guard('web')->user()->SuperVisor_Name }}
                                @endif
                            </span>
                            <svg class="fill-current h-4 w-4 opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(! Auth::guard('admin')->check())
                            <x-dropdown-link :href="route('profile.edit')">
                                الملف الشخصي
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                تسجيل الخروج
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/10 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-indigo-700">
        <div class="pt-2 pb-3 space-y-1 px-3">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10' }}">
                🏠 لوحة التحكم
            </a>

            @if(Auth::guard('admin')->check())
                <a href="{{ route('directorates.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('directorates.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10' }}">
                    🏢 المديريات
                </a>
                <a href="{{ route('schools.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('schools.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10' }}">
                    🏫 المدارس
                </a>
                <a href="{{ route('supervisors.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('supervisors.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10' }}">
                    👤 المشرفون
                </a>
                <a href="{{ route('teachers.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('teachers.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10' }}">
                    🧑‍🏫 المعلمون
                </a>

                <a href="{{ route('teacher-grades.sheet') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('teacher-grades.sheet') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10' }}">
                    📊 جدول الدرجات
                </a>
            @else
                <a href="{{ route('teachers.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('teachers.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10' }}">
                    🧑‍🏫 معلموي
                </a>

                <a href="{{ route('teacher-grades.sheet') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('teacher-grades.sheet') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10' }}">
                    📊 جدول الدرجات
                </a>


            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-white/20">
            <div class="px-4">
                <div class="font-medium text-base text-white">
                    @if(Auth::guard('admin')->check())
                        {{ Auth::guard('admin')->user()->name }}
                    @else
                        {{ Auth::guard('web')->user()->SuperVisor_Name }}
                    @endif
                </div>
            </div>

            <div class="mt-3 space-y-1 px-3">
                @if(! Auth::guard('admin')->check())
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10">
                        الملف الشخصي
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-right flex items-center px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>