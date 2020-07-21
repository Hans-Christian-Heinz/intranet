<!DOCTYPE html>
<html lang="en">
<head>
    <title>Projektantrag {{ $project->user->full_name }}</title>
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
    @include('pdf.antrag.titelseite')

    <sethtmlpagefooter name="footer" value="on"/>

    <tocpagebreak links="on" toc-prehtml="&lt;h3 class=&quot;heading&quot;&gt;Inhaltsverzeichnis&lt;/h3&gt;"></tocpagebreak>

    @foreach($version->sections()->where('sections.proposal_id', $proposal->id)->orderBy('sequence')->get() as $section)
        <tocentry content="{{ $section->heading }}" level="0"/>
        @include('pdf.section', ['tiefe' => 1,])
    @endforeach

    <tocentry content="Hinweis!" level="0"/>
    <h1 class="heading">Hinweis!</h1>
    <p class="abschnitt">Ich bestätige, dass der Projektantrag dem Ausbildungsbetrieb vorgelegt und vom Ausbildenden genehmigt wurde. Der
        Projektantrag enthält keine Betriebsgeheimnisse. Soweit diese für die Antragsstellung notwendig sind, wurden nach
        Rücksprache mit dem Ausbildenden die entsprechenden Stellen unkenntlich gemacht.
        Mit dem Absenden des Projektantrages bestätige ich weiterhin, dass der Antrag eigenständig von mir angefertigt wurde.
        Ferner sichere ich zu, dass im Projektantrag personenbezogene Daten (d.h. Daten über die eine Person identifizierbar oder
        bestimmbar ist) nur verwendet werden, wenn die betroffene Person hierin eingewilligt hat.
        Bei meiner ersten Anmeldung im Online-Portal wurde ich darauf hingewiesen, dass meine Arbeit bei Täuschungshandlungen
        bzw. Ordnungsverstößen mit "null" Punkten bewertet werden kann. Ich bin weiter darüber aufgeklärt worden, dass dies auch
        dann gilt, wenn festgestellt wird, dass meine Arbeit im Ganzen oder zu Teilen mit der eines anderen Prüfungsteilnehmers
        übereinstimmt. Es ist mir bewusst, dass Kontrollen durchgeführt werden.</p>
</body>
</html>