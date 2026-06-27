<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            #print-outlet { display: none; }

            @media print {
                @page { size: A4 portrait; margin: 0.5cm; }
                body { padding: 0; margin: 0; background: #fff; }

                body.printing-record > *:not(#print-outlet) { display: none !important; }
                body.printing-record #print-outlet { display: block !important; }

                .report-page { max-width: 800px; margin: 0 auto; padding: 10px; font-family: 'Tahoma','Arial',sans-serif; color:#111; }
                .header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
                .header-block { font-size: 13px; line-height: 1.6; }
                .header-block.left { text-align: left; }
                .header-block.right { text-align: right; }
                .header-block.center { text-align: center; }
                .header-logo { text-align: center; }
                .header-logo img { width: 60px; height: 60px; }
                h1.report-title { text-align: center; font-size: 18px; margin: 10px 0 14px; border-bottom: 2px solid #333; padding-bottom: 8px; }
                table.info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 13px; }
                table.info-table td { border: 1px solid #333; padding: 6px 10px; }
                table.info-table td.label { font-weight: bold; background: #f3f4f6; width: 16%; }
                table.info-table td.value { width: 34%; }
                table.scores-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
                table.scores-table th, table.scores-table td { border: 1px solid #333; padding: 5px 8px; text-align: center; }
                table.scores-table thead th { background: #e5e7eb; font-weight: bold; }
                table.scores-table td.indicator-cell { text-align: right; }
                table.scores-table td.group-cell { font-weight: bold; background: #f9fafb; }
                table.scores-table tfoot td { font-weight: bold; background: #f3f4f6; }
                .footer-row { display: flex; justify-content: space-between; margin-top: 14px; font-size: 13px; }
                .notes-line { margin-top: 14px; font-size: 13px; }
                .signature-line { margin-top: 30px; font-size: 13px; text-align: left; }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div id="print-outlet"></div>

        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        {{ $header }}
    </div>
</header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            window.printTeacherReport = async function (teacherId) {
                const outlet = document.getElementById('print-outlet');
                const res = await fetch(`/teachers/${teacherId}/report-html`);
                outlet.innerHTML = await res.text();

                document.body.classList.add('printing-record');
                window.print();
                document.body.classList.remove('printing-record');
                outlet.innerHTML = '';
            };
        </script>
    </body>
    </body>
</html>
