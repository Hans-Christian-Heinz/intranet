<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bildvorschau</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div style="page-break-inside: avoid">
        <img src="{{ asset('storage/' . $image->path) }}" height="{{ $height }}"
             width="{{ $width }}" alt="Die Bilddatei konnte nicht gefunden werden."/>
        <br/>
        <span class="footnote">Abb X: {{ $footnote }}</span>
    </div>
</body>
</html>
