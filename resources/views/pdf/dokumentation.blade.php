<!DOCTYPE html>
<html lang="en">
<head>
    <title>Projektdokumentation {{ $project->user->full_name }}</title>
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
            padding: 10% 0;
        }
        div#titelseite p {
            color: {{ $format['textfarbe'] }};;
        }
        abschnitt {
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
        caption {
            caption-side: bottom;
            text-align: left;
            margin-top: 0;
            padding-top: 0;
        }
        td, th {
            border: 1px solid black;
            padding: 0.5em;
        }
        tr:nth-child(even) {
            background: {{ $format['koerperBackground'] }}
        }
        tr:nth-child(odd) {
            background: {{ $format['koerperHintergrund'] }}
        }
        th {
            background-color: {{ $format['kopfHintergrund'] }};
            color: {{ $format['kopfText'] }};
            text-align: left;
        }
        tr.bgHeader {
            background-color: {{ $format['kopfHintergrund'] }};
        }
        tr.bgHeader th, tr.bgHeader td {
            color: {{ $format['kopfText'] }};
            text-align: left;
        }
        tr.bg0 {
            background-color: {{ $format['koerperBackground'] }};
        }
        tr.bg1 {
            background-color: {{ $format['koerperHintergrund'] }};;
        }
        tr.bg0 td, tr.bg1 td, tr.bg0 th, tr.bg1 th {
            color: {{ $format['koerperText'] }};
            text-align: left;
        }
    </style>
</head>
<body>
<sethtmlpageheader name="header" value="on"/>

@include('pdf.dokumentation.titelseite')

<sethtmlpagefooter name="footer" value="on"/>

<tocentry content="Inhaltsverzeichnis" level="0"/>
{{-- Beachten Sie: Ausgabe mit {{}} benutzt automatisch die Methode htmlspecialchars --}}
{{--<tocpagebreak links="on" toc-resetpagenum="1" toc-prehtml="{!! htmlspecialchars('<h3 class="heading">Inhaltsverzeichnis</h3>', ENT_QUOTES) !!}"></tocpagebreak>--}}
<tocpagebreak links="on" toc-resetpagenum="1" toc-prehtml="{{ '<h3 class="heading">Inhaltsverzeichnis</h3>' }}"></tocpagebreak>

<tocentry content="Tabellenverzeichnis" level="0"/>
{{-- Tabellenverzeichnis --}}
<tocpagebreak links="on" toc-prehtml="{{ '<h3 class="heading">Tabellenverzeichnis</h3>' }}" name="toc_tables"></tocpagebreak>

<tocentry content="Abbildungsverzeichnis" level="0"/>
{{-- Abbildungsverzeichnis --}}
<tocpagebreak links="on" toc-prehtml="{{ '<h3 class="heading">Abbildungsverzeichnis</h3>' }}" name="toc_img"></tocpagebreak>

@foreach($version->sections()->where('sections.documentation_id', $documentation->id)->orderBy('sequence')->get() as $section)
    @if($section->name === 'title')
        @continue
    @endif

    {{-- Eidesstattliche Erklärung soll vor dem Anhang kommen --}}
    @if($loop->last && $section->name == 'anhang')
        @include('pdf.dokumentation.eidesstattliche_erklaerung')
    @endif

    @if($section->counter == 'inhalt')
        <tocentry content="{{ $inhalt_counter->nextNumber() . '.  ' . $section->heading }}" level="0"/>
    @else
        <tocentry content="{{ $section->heading }}" level="0"/>
    @endif
    @include('pdf.section', ['tiefe' => 1,])

    {{-- Eidesstattliche Erklärung muss auch vorkommen, wenn kein Anhang vorliegt (oder der Anahng nicht am Ende steht) --}}
    @if($loop->last && $section->name != 'anhang')
        @include('pdf.dokumentation.eidesstattliche_erklaerung')
    @endif
@endforeach
</body>
</html>
