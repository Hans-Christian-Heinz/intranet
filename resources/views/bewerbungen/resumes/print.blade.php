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
                <img style="height: 45mm; width: 35mm;" src="{{ storage_path('app/temp/' . $format['passbild']) }}" alt="Kein Passbild hochgeladen"/>
            </td>
        @endif
    </tr>
</table>

<table style="width: 100%; margin-top: 10px;">
    <tbody>
    <tr>
        <td style="width: 50%;">
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

            @if($content->school)
                <br><br>
                <h3 class="heading">Schulische Laufbahn</h3>
                <hr>
                @foreach ($content->school as $index => $school)
                    <p><strong>{{ $school->time }}</strong></p>
                    <p>{{ $school->school }}: {{ $school->abschluss }}</p>
                    @if ($index !== count($content->education) - 1)
                        <br>
                    @endif
                @endforeach
            @endif


            @if($content->education)
                <br><br>
                <h3 class="heading">Ausbildung</h3>
                <hr>
                @foreach ($content->education as $index => $education)
                    <p><strong>{{ $education->time }}</strong></p>
                    <p>{{ $education->description }}{{ isset($education->abschluss) ? ' Abschluss: ' . $education->abschluss :'' }}</p>
                    @if ($index !== count($content->education) - 1)
                        <br>
                    @endif
                @endforeach
            @endif

            @if($content->careers)
                <br><br>
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
            @endif
        </td>
        <td valign="top" style="width: 50%;">
            {{-- Right Side --}}
            @if($content->internships)
                <h3 class="heading">Praktika</h3>
                <hr>
                @foreach ($content->internships as $index => $internship)
                    <p><strong>{{ $internship->time }}</strong></p>
                    <p><strong>{{ $internship->company }}</strong></p>
                    <p>{{ $internship->description }}</p>
                    @if ($index !== count($content->skills) - 1)
                        <br>
                    @endif
                @endforeach
            @endif

            @if($content->skills)
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
            @endif

            @if($content->interests)
                <br><br>
                <h3 class="heading">Interessen</h3>
                <hr>
                @foreach ($content->interests as $index => $interest)
                    <p>{{ $interest->interest }}</p>
                    @if ($index !== count($content->skills) - 1)
                        <br>
                    @endif
                @endforeach
            @endif
        </td>
    </tr>
    </tbody>
</table>

@if($format['signature'])
    <img style="margin-top: 10mm" height="60" width="350" src="{{ storage_path('app/temp/' . $format['signature']) }}" alt="Keine Signatur hochgeladen"/>
@endif

<p @unless($format['signature']) style="margin-top: 60px;"  @endunless>{{ $content->personal->name }}, Winnenden, den {{ Carbon\Carbon::now()->format("d.m.Y") }}</p>

</body>
