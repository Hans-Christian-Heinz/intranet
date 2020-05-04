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

            .item {
                border: 1px solid #000;
                border-bottom: none;
                font-size: 10pt;
            }

            .item p {
                padding: 10px;
                margin: 0px;
            }

            .item .title {
                background-color: #c8c6c6;
                border-bottom: 1px solid #000;
                padding: 6px 10px;
                font-weight: bold;
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
        <div>
            <img src="{{ asset("/img/ihk_logo.png") }}">
            <span style="font-size: 25px;">Region Stuttgart</span>
        </div>
        <p><strong>Anlage 2 b: Ausbildungsnachweis (wöchentlich)</strong></p>

        <table border="1" style="width: 100%; font-size: 10pt;">
            <tbody>
                <tr>
                    <td style="width: 28%;">Name des/der<br /> Auszubildenden:</td>
                    <td colspan="3">{{ auth()->user()->name }}</td>
                </tr>
                <tr>
                    <td>Ausbildungsjahr:</td>
                    <td>{{ $berichtsheft->grade }}</td>
                    <td>Ggf. ausbildende<br /> Abteilung:</td>
                    <td>Paulinenpflege<br /> Winnenden e.V. /<br /> Informationstechnik</td>
                </tr>
                <tr>
                    <td>Ausbildungswoche vom:</td>
                    <td>{{ $berichtsheft->week->startOfWeek()->format("d.m.Y") }}</td>
                    <td>bis:</td>
                    <td>{{ $berichtsheft->week->endOfWeek()->format("d.m.Y") }}</td>
                </tr>
            </tbody>
        </table>

        <div class="item" style="margin-top: 20px;">
            <p class="title">Betriebliche Tätigkeiten:</p>
            <p>{{ $berichtsheft->work_activities }}</p>
        </div>

        <div class="item">
            <p class="title">Unterweisungen, betrieblicher Unterricht, sonstige Schulungen:</p>
            <p>{{ $berichtsheft->instructions }}</p>
        </div>

        <div class="item" style="border-bottom: 1px solid #000;">
            <p class="title">Themen des Berufsschulunterrichts:</p>
            <p>{{ $berichtsheft->school }}</p>
        </div>

        <p style="font-size: 9.5pt;">Durch die nachfolgende Unterschrift wird die Richtigkeit und Vollständigkeit der obigen Angaben bestätigt.</p>

        <table style="width: 100%; margin-top: 35px;">
            <tbody>
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <p class="signature">Datum, Unterschrift Auszubildende/r&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <br>
                        <p class="signature_extra">Zur Kenntnis genommen:</p>
                        <br><br>
                        <p class="signature">Datum, Unterschrift gesetzliche/r Vertreter/in</p>
                    </td>
                    <td style="vertical-align: top;">
                        <p class="signature">Datum, Unterschrift Ausbildende/r oder Ausbilder/in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <br>
                        <p class="signature_extra">Sonstige Sichtvermerke:</p>
                        <br><br>
                        <p class="signature">Datum, Unterschrift Betriebsrat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <br><br>
                        <p class="signature">Datum, Unterschrift Berufsschule&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
