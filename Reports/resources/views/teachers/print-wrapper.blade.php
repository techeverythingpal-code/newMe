<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>طباعة</title>
    
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