<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bewerbungsanschreiben {{ $resume->personal->name }}</title>
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
        @foreach($content as $key => $c)
            @unless($key == 'attachments')
                @if($c['is_heading'])
                    <h2 class="heading">{{ $c['text'] }}</h2>
                @else
                    <p>{{ $c['text'] }}</p>
                @endif
            @endunless
        @endforeach
    </div>

    <p>Mit freundlichen Grüßen</p>

    {{--<img height="60" width="350" src="data:image/{{ $format['sig_datatype'] }};base64,{{ $format['signature'] }}" alt="Keine Signatur hochgeladen"/>--}}
    <img height="60" width="350" src="{{ storage_path('app/temp/' . $format['signature']) }}" alt="Keine Signatur hochgeladen"/>

    <p>{{ $resume->personal->name }}, Winnenden, den {{ Carbon\Carbon::now()->format("d.m.Y") }}</p>

    @if(isset($content['attachments']) && $content['attachments'])
        <div style="page-break-inside: avoid; font-size: 0.9rem;">
            <b>Anlagen:</b>
            <ul>
                @foreach($content['attachments'] as $att)
                    <li>{{ $att }}</li>
                @endforeach
            </ul>
        </div>
    @endif

</body>
