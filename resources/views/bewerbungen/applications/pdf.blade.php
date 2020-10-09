<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lebenslauf {{ $resume->personal->name }}</title>
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
            color: {{ $format['textfarbe'] }};
        }
        .heading {
            color: {{ $format['ueberschrFarbe'] }};
        }
    </style>
</head>
<body>
<sethtmlpagefooter name="footer" value="on"/>

<table style="width: 100%;">
    <tbody>
    <tr>
        <td>
            <strong>{{ $application->company->name }}</strong><br>
            {{ $application->company->address }}<br>
            {{ $application->company->zip . " " . $application->company->city }}<br>
        </td>
        <td style="text-align: right;">
            {{ $resume->personal->name }}<br>
            {{ $resume->personal->address }}<br>
            {{ $resume->personal->zip . " " . $resume->personal->city }}<br>
            {{ $resume->personal->phone }}<br>
            {{ $resume->personal->email }}
        </td>
    </tr>
    </tbody>
</table>

<div style="margin-top: 50px">
    @foreach($res as $c)
        <p>{{ $c }}</p>
    @endforeach
</div>

{{--
<p style="margin-top: 50px;">{{ $content->greeting->body }}</p>

<p>{{ $content->awareofyou->body }}</p>

<p>{{ $content->currentactivity->body }}</p>

<p>{{ $content->whycontact->body }}</p>

@php
    $wayOfWork = $content->wayOfWork;
    $lastWayOfWork = empty($wayOfWork) ? "" :$content->wayOfWork[count($content->wayOfWork) - 1];
    $skills = $content->skills;
    $lastSkill = empty($skills) ? "" : $content->skills[count($content->skills) - 1];

    if (count($wayOfWork) > 1) {
        array_pop($wayOfWork);
    }

    if (count($skills) > 1) {
        array_pop($skills);
    }

    $wayOfWorkMessage = (count($content->wayOfWork) > 1) ? join(", ", $wayOfWork) . " und " . $lastWayOfWork : $lastWayOfWork;
    $skillsMessage = (count($content->skills) > 1) ? join(", ", $skills) . " und " . $lastSkill : $lastSkill;

    $finalMessage = "In eine neue Aufgabe bei Ihnen kann ich verschiedene Stärken einbringen. So bin ich meine Aufgaben sehr {$wayOfWorkMessage} angegangen. Mit mir gewinnt Ihr Unternehmen einen Mitarbeiter, der {$skillsMessage} ist. Außerdem habe ich in früheren Projekten insbesondere ausgeprägte Kommunikationsstärke, hohe Lernbereitschaft und viel Kreativität unter Beweis stellen können.";
@endphp

<p>{{ $finalMessage }}</p>

<p>{{ $content->ending->body }}</p>
--}}

<p>Mit freundlichen Grüßen</p>

<p style="margin-top: 60px;">{{ $resume->personal->name }}, {{ Carbon\Carbon::now()->format("d.m.Y") }}</p>

</body>

