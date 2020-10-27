<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lebenslauf {{ $content->personal->name }}</title>
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

<table style="border: none; width: 100%;">
    <tr>
        <td>
            <h1 class="heading">{{ $content->personal->name }}</h1>
        </td>
        @if($format['passbild'])
            <td style="text-align: right">
                <img style="height: 45mm; width: 35mm;" src="data:image/{{ $format['pb_datatype'] }};base64,{{ $format['passbild'] }}" alt="Kein Passbild hochgeladen"/>
            </td>
        @endif
    </tr>
</table>

<table style="width: 100%; margin-top: 10px;">
    <tbody>
    <tr>
        <td>
            {{-- Left Side --}}
            <h3 class="heading">Persönliche Daten</h3>
            <hr>

            <p><strong>Name</strong></p>
            <p>{{ $content->personal->name }}</p>

            <p><strong>Anschrift</strong></p>
            <p>{{ $content->personal->address }} <br> {{ $content->personal->zip }} {{ $content->personal->city }}</p>

            <p><strong>Tel.</strong></p>
            <p>{{ $content->personal->phone }}</p>

            <p><strong>E-Mail</strong></p>
            <p>{{ $content->personal->email }}</p>

            <p><strong>geb.</strong></p>
            <p>{{ (new Carbon\Carbon($content->personal->birthday))->format("d.m.Y") }}</p>

            <br><br>
            <h3 class="heading">Ausbildung</h3>
            <hr>
            @foreach ($content->education as $index => $education)
                <p><strong>{{ $education->time }}</strong></p>
                <p>{{ $education->description }}</p>
                @if ($index !== count($content->education) - 1)
                    <br>
                @endif
            @endforeach

            <br><br>
            <h3 class="heading">Kenntnisse & Fähigkeiten</h3>
            <hr>
            @foreach ($content->skills as $index => $skill)
                <p><strong>{{ $skill->title }}</strong></p>
                <p>{{ $skill->description }}</p>
                @if ($index !== count($content->skills) - 1)
                    <br>
                @endif
            @endforeach
        </td>
        <td valign="top">
            {{-- Right Side --}}
            <h3 class="heading">Berufliche Laufbahn</h3>
            <hr>
            @foreach ($content->careers as $index => $career)
                <p><strong>{{ $career->time }}</strong></p>
                <p><strong>{{ $career->company }}</strong></p>
                <p>{{ $career->description }}</p>
                @if ($index !== count($content->skills) - 1)
                    <br>
                @endif
            @endforeach
        </td>
    </tr>
    </tbody>
</table>

<img style="margin-top: 10mm" height="60" width="350" src="data:image/{{ $format['sig_datatype'] }};base64,{{ $format['signature'] }}" alt="Keine Signatur hochgeladen"/>

<p>{{ $content->personal->name }}, Winnenden, den {{ Carbon\Carbon::now()->format("d.m.Y") }}</p>

</body>
