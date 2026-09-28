{{-- Mobile backdrop --}}
<div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-black/40 lg:hidden" style="display: none;"></div>

@php
    $isAdmin  = Auth::guard('admin')->check();
    $userName = $isAdmin
        ? Auth::guard('admin')->user()->name
        : Auth::guard('web')->user()->SuperVisor_Name;

    $links = [
        ['route' => 'dashboard', 'active' => 'dashboard', 'icon' => '🏠', 'label' => 'لوحة التحكم'],
    ];

    if ($isAdmin) {
        $links[] = ['route' => 'directorates.index', 'active' => 'directorates.*', 'icon' => '🏢', 'label' => 'المديريات'];
        $links[] = ['route' => 'schools.index',      'active' => 'schools.*',      'icon' => '🏫', 'label' => 'المدارس'];
        $links[] = ['route' => 'supervisors.index',  'active' => 'supervisors.*',  'icon' => '👤', 'label' => 'المشرفون'];
        $links[] = ['route' => 'teachers.index',     'active' => 'teachers.*',     'icon' => '🧑‍🏫', 'label' => 'المعلمون'];
    }
    if (! $isAdmin) {
    $links[] = ['route' => 'teacher-grades.sheet', 'active' => 'teacher-grades.sheet', 'icon' => '📊', 'label' => 'جدول الدرجات'];
}

    

    // Supervisor-only action groups, shown on the dashboard page
    $showDashboardActions = ! $isAdmin && request()->routeIs('dashboard');

    $itemClass   = 'w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-r-full text-white/80 hover:bg-white/10 hover:text-white transition text-right';
    $dangerClass = 'w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-r-full text-red-200 hover:bg-red-500/30 hover:text-white transition text-right';
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'"
    class="fixed inset-y-0 right-0 z-50 w-64 bg-brand-500 text-white transition-transform duration-200 lg:static lg:z-auto lg:translate-x-0 lg:shrink-0">

    <div class="h-full lg:h-screen lg:sticky lg:top-0 flex flex-col overflow-y-auto">

        {{-- Logo --}}
        <div class="flex items-center justify-between px-5 h-20 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-lg">
                <span class="text-2xl">📋</span>
                <span>{{ config('app.name') }}</span>
            </a>
            <button type="button" @click="sidebarOpen = false"
                class="lg:hidden p-1 rounded-md hover:bg-white/10 focus:outline-none" aria-label="إغلاق">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Links: the active item is a light pill that merges into the page background --}}
        <nav class="flex-1 pr-3 space-y-1 mt-2">
            @foreach($links as $link)
                @php $active = request()->routeIs($link['active']); @endphp
                <a href="{{ route($link['route']) }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-r-full transition
                        {{ $active
                            ? 'bg-brand-100 text-brand-700 font-bold'
                            : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">{{ $link['icon'] }}</span>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach

            @if($showDashboardActions)
                {{-- Group 1: teachers management --}}
                <div class="pt-5">
                    <div class="px-4 pb-1 text-xs font-bold text-white/50">إدارة المعلمين</div>
                    <div class="space-y-1">
                        <a href="{{ route('teachers.create') }}" class="{{ $itemClass }}">
                            <span class="text-lg">➕</span><span>إضافة معلم</span>
                        </a>
                        <a href="{{ route('teachers.export') }}" class="{{ $itemClass }}">
                            <span class="text-lg">📥</span><span>تصدير Excel</span>
                        </a>
                        <form action="{{ route('teacher-grades.reset-all') }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف درجات جميع معلميك؟ لا يمكن التراجع عن هذا الإجراء.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="{{ $dangerClass }}">
                                <span class="text-lg">🗑️</span><span>حذف كل الدرجات</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Group 2: reports & printing (handled by the dashboard page's script) --}}
                <div class="pt-5">
                    <div class="px-4 pb-1 text-xs font-bold text-white/50">التقارير والطباعة</div>
                    <div class="space-y-1">
                        <button type="button" class="{{ $itemClass }}"
                            @click="$dispatch('dashboard-action', 'printAll'); sidebarOpen = false">
                            <span class="text-lg">🖨️</span><span>طباعة الكل</span>
                        </button>
                        <button type="button" class="{{ $itemClass }}"
                            @click="$dispatch('dashboard-action', 'printSummary'); sidebarOpen = false">
                            <span class="text-lg">📊</span><span>التقرير الموجز</span>
                        </button>
                        <button type="button" class="{{ $itemClass }}"
                            @click="$dispatch('dashboard-action', 'printList'); sidebarOpen = false">
                            <span class="text-lg">📄</span><span>قائمة المعلمين</span>
                        </button>
                    </div>
                </div>
            @endif
        </nav>

        {{-- User block --}}
        <div class="px-4 pb-5 pt-4 mt-4 border-t border-white/15 shrink-0">
            <div class="flex items-center gap-3 mb-3">
                <span class="w-10 h-10 shrink-0 rounded-full bg-white/20 flex items-center justify-center font-bold">
                    {{ mb_substr($userName ?? '', 0, 1) }}
                </span>
                <div class="min-w-0">
                    <div class="text-sm font-bold truncate">{{ $userName }}</div>
                    <div class="text-xs text-white/60">{{ $isAdmin ? 'مدير النظام' : 'مشرف' }}</div>
                </div>
            </div>

            <div class="space-y-1">
                @if(! $isAdmin)
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white/80 hover:bg-white/10 hover:text-white transition">
                        <span>⚙️</span> الملف الشخصي
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white/80 hover:bg-white/10 hover:text-white transition text-right">
                        <span>🚪</span> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>