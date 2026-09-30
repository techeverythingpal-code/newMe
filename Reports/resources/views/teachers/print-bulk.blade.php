diff --git a/Reports/resources/views/teachers/print-bulk.blade.php b/Reports/resources/views/teachers/print-bulk.blade.php
index 1dff3ec..60ffad5 100644
--- a/Reports/resources/views/teachers/print-bulk.blade.php
+++ b/Reports/resources/views/teachers/print-bulk.blade.php
@@ -6,13 +6,6 @@
     <style>
         * { box-sizing: border-box; }
         body { font-family: 'Tahoma','Arial',sans-serif; color:#111; background:#f3f4f6; margin:0; padding:20px; }
-        .toolbar { max-width:800px; margin:0 auto 14px; display:flex; justify-content:space-between; align-items:center; gap:8px; }
-        .toolbar button {
-            background:#2563eb; color:#fff; border:none; padding:8px 18px;
-            border-radius:8px; font-weight:bold; cursor:pointer; font-size:14px;
-        }
-        .toolbar button:hover { background:#1d4ed8; }
-        .toolbar span { font-size:13px; color:#555; }
         .report-page { max-width: 800px; margin: 0 auto 20px; padding: 20px; background:#fff; }
         .header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; margin-top:10px; }
         .header-block { font-size: 13px; line-height: 1.6; }
@@ -61,7 +54,6 @@
         @media print {
             @page { size: A4 portrait; margin: 0.5cm; }
             body { padding: 0; background: #fff; }
-            .toolbar { display: none; }
             .academic-year-input { border: none !important; background: transparent !important; }
             .report-page { page-break-after: always; }
             .report-page:last-of-type { page-break-after: auto; }
@@ -70,11 +62,6 @@
 </head>
 <body>
 
-    <div class="toolbar">
-        <span>عدد التقارير: {{ $teachers->count() }}</span>
-        <button onclick="window.print()">🖨️ طباعة التقارير المختارة</button>
-    </div>
-
     @foreach($teachers as $teacher)
         @include('teachers.partials.report-body', ['teacher' => $teacher, 'criteria' => $criteria, 'groups' => $groups, 'academicYear' => $academicYear])
     @endforeach