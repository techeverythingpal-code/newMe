<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
            لوحة التحكم - {{ auth()->user()->SuperVisor_Name }}
        </h2>
    </x-slot>

<style>
    .rtl-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23666'%3E%3Cpath d='M5.5 7l4.5 4.5L14.5 7' stroke='%23666' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 10px center;
    background-size: 14px;
    padding-left: 32px;
}

.view-toggle-btn {
    color: #6b7280;
}

.view-toggle-btn.bg-white {
    color: #2563eb;
}
</style>

    <div class="py-6" dir="rtl">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

                <button type="button" id="cardAllTeachers"
                    class="stat-card bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg text-right hover:scale-[1.02] transition cursor-pointer">
                    <div class="text-4xl mb-2">👨‍🏫</div>
                    <div class="text-3xl font-bold">{{ $totalTeachers }}</div>
                    <div class="text-sm opacity-80 mt-1">إجمالي المعلمين</div>
                </button>

                <button type="button" id="cardAvgTotal"
                    class="stat-card bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg text-right hover:scale-[1.02] transition cursor-pointer">
                    <div class="text-4xl mb-2">📊</div>
                    <div class="text-3xl font-bold">{{ number_format($avgTotal, 1) }}</div>
                    <div class="text-sm opacity-80 mt-1">متوسط الدرجات</div>
                </button>

                <button type="button" id="cardHighestScore"
                    class="stat-card bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white shadow-lg text-right hover:scale-[1.02] transition cursor-pointer">
                    <div class="text-4xl mb-2">🏆</div>
                    <div class="text-3xl font-bold">{{ $highestScore }}</div>
                    <div class="text-sm opacity-80 mt-1">أعلى درجة</div>
                </button>

                <button type="button" id="cardExcellent" data-min-score="85"
                    class="stat-card bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-5 text-white shadow-lg text-right hover:scale-[1.02] transition cursor-pointer">
                    <div class="text-4xl mb-2">⭐</div>
                    <div class="text-3xl font-bold">{{ $excellentCount }}</div>
                    <div class="text-sm opacity-80 mt-1">تقدير ممتاز</div>
                </button>

            </div>

            {{-- My Teachers Table --}}
            <div class="bg-white rounded-2xl shadow p-5">
               <div class="flex justify-between items-center mb-4">
                    
                    <div class="flex items-center gap-3">
                    <h3 class="font-semibold text-gray-700">عرض بيانات المعلمين : </h3>
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button type="button" id="viewCardsBtn"
                                class="view-toggle-btn px-3 py-1.5 rounded-md text-sm font-bold transition">
                                🔲 بطاقات
                            </button>
                            <button type="button" id="viewTableBtn"
                                class="view-toggle-btn px-3 py-1.5 rounded-md text-sm font-bold transition">
                                🗂️ قائمة
                            </button>
                        </div>
                        
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('teachers.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                            + إضافة معلم
                        </a>
                        <a href="{{ route('teachers.export') }}"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                            📥 تصدير Excel
                        </a>
                        <form action="{{ route('teacher-grades.reset-all') }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف درجات جميع معلميك؟ لا يمكن التراجع عن هذا الإجراء.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                                🗑️ حذف كل الدرجات
                            </button>
                        </form>
                    </div>
                
                </div>

                {{-- Academic year + bulk print --}}
                <div class="flex flex-wrap items-center gap-3 mb-4 bg-gray-50