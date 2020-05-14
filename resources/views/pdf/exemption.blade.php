<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            table {
                border-collapse: collapse;
            }

            tr td {
                padding: 5px;
            }

            .signature {
                font-size: 8pt;
                border-top: 1px solid #000;
                width: 550mm;
            }

            .signature_extra {
                font-size: 9pt;
            }
        </style>
    </head>
    <body>
        <h1>Antrag auf Freistellung</h1>
        <p>
            Ich, <strong>{{ $applicant }}</strong>,
            <br><br>
            beantrage Freistellung von der Arbeitszeit/vom Unterricht
            <br><br>
            vom
            <strong>
                @if ($exemption->start->format('H:i') === '00:00')
                    {{ $exemption->start->format('d. M Y') }}
                @else
                    {{ $exemption->start->format('d. M Y H:i') }}
                @endif
            </strong>

            bis
            <strong>
                @if ($exemption->end->format('H:i') === '00:00')
                    {{$exemption->end->format('d. M Y') }}
                @else
                    {{$exemption->end->format('d. M Y H:i') }}
                @endif
            </strong>

            <br><br>
            Grund: {{ $exemption->reason }}
        </p>

        <table style="width: 100%; margin-top: 35px;">
            <tbody>
                <tr>
                    <td style="width: 30%; vertical-align: top;">Unterschrift:</td>
                    <td style="vertical-align: top;">
                        <br>
                        <p class="signature">
                            Auszubildende/r Schüler/in
                            @for ($i = 0; $i < 89; $i++)&nbsp;@endfor
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>Freistellung genehmigt:</td>
                    <td style="vertical-align: top;">
                        <br><br>
                        <p class="signature">
                            Werkstattleiter/in
                            @for ($i = 0; $i < 20; $i++)&nbsp;@endfor
                            Klassenlehrer/in
                            @for ($i = 0; $i < 20; $i++)&nbsp;@endfor
                            Schulleiter/in
                            @for ($i = 0; $i < 10; $i++)&nbsp;@endfor
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p>
            <br><br>
            &#x2610; Internatsleitung<br>
            &#x2610; Ausbildungs-/Schulleitung<br>
            &#x2610; Werkstatt/Schule<br>
        </p>

        <img src="{{ asset("/img/bbw_logo.png") }}" style="margin-left: 50%;">
    </body>
</html>
