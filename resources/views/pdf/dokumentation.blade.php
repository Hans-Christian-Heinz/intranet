<!DOCTYPE html>
<html lang="en">
<head>
    <title>Projektdokumentation {{ $project->user->full_name }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .courier {
            font-family: courier, serif;
        }
        .helvetica {
            font-family: helvetica, serif;
        }
        .times {
            font-family: "Times New Roman", Times, serif;
        }
        .b {
            font-weight: bold;
        }
        .i {
            font-style: italic;
        }
        div#titelseite {
            page-break-after: right;
            text-align: center;
            padding: 25% 0;
        }
        div#titelseite p {
            color: {{ $format['textfarbe'] }};;
        }
        p.abschnitt {
            text-align: justify;
            color: {{ $format['textfarbe'] }};
        }
        .heading {
            color: {{ $format['ueberschrFarbe'] }};
        }
        table {
            width: 100%;
            margin-bottom: 1rem;
            border: solid black 1px;
            page-break-inside: avoid;;
        }
        td, th {
            border: solid black 1px;
            padding: 0.5em;
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
<body class="{{ $format['textart'] }}">
@include('pdf.dokumentation.titelseite')

<tocpagebreak links="on" toc-prehtml="&lt;h3 class=&quot;heading&quot;&gt;Inhaltsverzeichnis&lt;/h3&gt;"></tocpagebreak>

@foreach($version->sections()->where('sections.documentation_id', $documentation->id)->orderBy('sequence')->get() as $section)
    @if($section->name === 'title')
        @continue
    @endif

    <tocentry content="{{ $section->heading }}" level="0"/>
    @include('pdf.section', ['tiefe' => 1,])
@endforeach
</body>
</html>
