<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vorschau PDF</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-size: {{ $format['textgroesse'] }}pt;
            @switch($format['textart'])
                @case('courier')
                    font-family: courier, serif;
            @break
        @case('helvetica')
font-family: helvetica, serif;
            @break
        @case('times')
font-family: "Times New Roman", Times, serif;
            @break
        @case('dejausans')
font-family: "DejaVu Sans", sans-serif;
        @break
@endswitch
}
        div#titelseite {
            page-break-after: right;
            text-align: center;
            padding: 25% 0;
        }
        div#titelseite p {
            color: {{ $format['textfarbe'] }};;
        }
        .abschnitt {
            text-align: justify;
            color: {{ $format['textfarbe'] }};
        }
        .heading {
            color: {{ $format['ueberschrFarbe'] }};
            page-break-after: avoid;
        }
        table {
            width: 100%;
            margin-bottom: 1rem;
            border: 1px solid black;
            page-break-inside: avoid;
            border-collapse: collapse;
        }
        tr, td, th {
            border: 1px solid black;
            padding: 0.5em;
        }
        tr.bgHeader {
            background-color: {{ $format['kopfHintergrund'] }};
        }
        tr.bgHeader th {
            color: {{ $format['kopfText'] }};
            text-align: left;
        }
        tr.bg0 {
            background-color: {{ $format['koerperBackground'] }};
        }
        tr.bg1 {
            background-color: {{ $format['koerperHintergrund'] }};;
        }
        tr.bg0 td, tr.bg1 td {
            color: {{ $format['koerperText'] }};
            text-align: left;
        }
    </style>
</head>
<body class="{{ $format['textart'] }}">
    <h1 class="heading">Überschrift</h1>
    <p class="abschnitt">
        @for($i = 0; $i < 50; $i++)
            Hier steht etwas Text.
        @endfor
    </p>

    <div class="abschnitt" style="page-break-inside: avoid">
        <table>
            @for($i = 0; $i < 4; $i++)
                <tr class="@if($i == 0) bgHeader @elseif($i % 2 == 0) bg0 @else bg1 @endif">
                    @if($i == 0)
                        <th>Kopfzeile</th><th>Kopfzeile</th>
                    @else
                        <td>Inhalt</td><td>Inhalt</td>
                    @endif
                </tr>
            @endfor
        </table>
        <span class="footnote">Tabelle 1: Fußnote</span>
    </div>
</body>
</html>
