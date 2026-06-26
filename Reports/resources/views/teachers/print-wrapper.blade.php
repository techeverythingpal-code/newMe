<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>طباعة</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            src: url("{{ storage_path('fonts/Amiri-Regular.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        html, body { margin: 0; padding: 0; height: 100%; }
        iframe { width: 100%; height: 100%; border: none; }
        body {
            font-family: 'Amiri', serif;
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>
    <iframe id="pdfFrame" src="{{ route('teacher-grades.print', $teacher->Teacher_id) }}"></iframe>

    <script>
        const frame = document.getElementById('pdfFrame');
        frame.onload = function () {
            setTimeout(function () {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            }, 300);
        };
    </script>
</body>
</html>