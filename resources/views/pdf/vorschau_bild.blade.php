<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bildvorschau</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <figure>
        <img src="{{ asset('storage/' . $image->path) }}" height="{{ $height }}mm"
             width="{{ $width }}mm" alt="Die Bilddatei konnte nicht gefunden werden."/>
        <figcaption>Abb X: {{ $footnote }}</figcaption>
    </figure>
</body>
</html>
