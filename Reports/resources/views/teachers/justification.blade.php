<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('teachers.show', $teacher->Teacher_id) }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-lg transition">
                ← العودة
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ملاحظات إضافية على اداء المعلم: {{ $teacher->Teacher_Name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow p-8">

                <div class="text-gray-600 mb-6 border-b pb-4">
                    رقم المعلم: {{ $teacher->Teacher_id }}
                </div>

                <form method="POST" action="{{ route('teachers.justification.store', $teacher->Teacher_id) }}">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            مواطن القوة (إنجازات أو نشاطات أخرى يتميز بها ولم تشتمل عليها العناصر السابقة):
                        </label>
                        <textarea name="strengths" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('strengths', $justification->strengths ?? '') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            مواطن الضعف (جوانب سلبية يتصف بها وتؤثر على عمله دون أن يكون هناك تكرار للعناصر السابقة):
                        </label>
                        <textarea name="weaknesses" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('weaknesses', $justification->weaknesses ?? '') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            التوجهات و التوصيات العامة لتطوير قدراته ( إن وجدت ):
                        </label>
                        <textarea name="recommendations" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('recommendations', $justification->recommendations ?? '') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            رأي معد التقرير:
                        </label>
                        <textarea name="preparer_opinion" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('preparer_opinion', $justification->preparer_opinion ?? '') }}</textarea>
                    </div>

                    <div class="mb-8">
                        <label class="block text-gray-700 font-medium mb-2">
                            ملحوظات معتمد التقرير:
                        </label>
                        <textarea name="approver_notes" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('approver_notes', $justification->approver_notes ?? '') }}</textarea>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <a href="{{ route('teachers.show', $teacher->Teacher_id) }}"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-lg transition">
                            إلغاء
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            إرسال الملاحظات
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>