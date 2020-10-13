<?php

namespace App\Http\Controllers;

use App\Application;
use App\Company;
use App\Http\Requests\PdfRequest;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ApplicationController extends Controller
{
    const STANDARD_TEMPLATES = '{
        "heading": {
            "heading" : "Überschrift",
            "is_heading": true,
            "fix": true,
            "number": 0,
            "tpls": [
                "Bewerbung auf die Stelle als Musterstelle",
                "Bewerbung auf die ausgeschriebene Stelle 12345"
            ]
        },
        "greeting": {
            "heading" : "Anrede",
            "fix": true,
            "number": 1,
            "tpls": [
                "Sehr geehrte Damen und Herren,",
                "Sehr geehrte Frau Musterfrau,",
                "Sehr geehrter Herr Mustermann,"
            ]
        },
        "awareofyou": {
            "heading": "Wie bist du auf diese Stelle aufmerksam geworden?",
            "fix": false,
            "number": 2,
            "tpls": [
                "mit großem Interesse bin ich im XING Stellenmarkt auf die ausgeschriebene Position aufmerksam geworden. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).",
                "auf der Suche nach einer neuen Beschäftigung bin ich auf Ihr Unternehmen aufmerksam geworden und komme jetzt initiativ auf Sie zu. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).",
                "wie telefonisch besprochen, interessiere ich mich sehr für eine Beschäftigung in Ihrem Unternehmen. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w)."
            ]
        },
        "currentactivity": {
            "heading": "Was ist deine derzeitige Beschäftigung?",
            "fix": false,
            "number": 3,
            "tpls": [
                "Zurzeit arbeite ich als Musterberuf bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                "Zurzeit studiere ich Musterstudiengang an der Musterhochschule. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                "Zurzeit befinde ich mich in der Ausbildung als Musterausbildung bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                "Zurzeit absolviere ich ein Praktikum im Bereich Musterstelle bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                "Zurzeit besuche ich die Musterschule in Musterort. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen."
            ]
        },
        "whycontact": {
            "heading": "Warum bewirbst du dich bei dem Unternehmen?",
            "fix": false,
            "number": 4,
            "tpls": [
                "Ihr Stellenangebot hört sich toll an! Ich hoffe, mir hierdurch persönliche und fachliche Entwicklungsmöglichkeiten erschließen zu können. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                "Nachdem ich schon länger in diesem Bereich tätig bin, suche ich jetzt nach einer neuen Position, in der ich mehr Verantwortung übernehmen kann. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                "Mein Wunsch ist es, die beschriebene Aufgabenstellung als nächsten Schritt für meine weitere berufliche Entwicklung in Ihrem Hause zu nutzen. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                "Ich suche an meinem neuen Wohnort eine interessante Beschäftigung und bin daher auf Ihr Unternehmen aufmerksam geworden. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen."
            ]
        },
        "workAndSkills": {
            "fix": false,
            "chooseKeywords": true,
            "number": 5,
            "text": [
                "In eine neue Aufgabe bei Ihnen kann ich verschiedene Stärken einbringen. So bin ich meine Aufgaben sehr",
                "angegangen. Mit mir gewinnt Ihr Unternehmen einen Mitarbeiter, der",
                "ist. Außerdem habe ich in früheren Projekten insbesondere ausgeprägte Kommunikationsstärke, hohe Lernbereitschaft und viel Kreativität unter Beweis stellen können."
            ],
            "keywords": [
                {
                    "heading": "Was zeichnet deine Arbeitsweise aus?",
                    "conjunction": "und",
                    "tpls": [
                        "zuverlässig",
                        "verantwortungsbewusst",
                        "präzise",
                        "engagiert",
                        "gewissenhaft",
                        "ausdauernd"
                    ]
                },
                {
                    "heading": "Welche Begriffe beschreiben am besten deine persönlichen Kompetenzen?",
                    "conjunction": "und",
                    "tpls": [
                        "flexibel",
                        "motiviert",
                        "teamorientiert",
                        "belastbar",
                        "selbsständig",
                        "aufgeschlossen",
                        "begeisterungsfähig"
                    ]
                }
            ]
        },
        "ending": {
            "heading": "Schlusswort",
            "fix": true,
            "number": 6,
            "tpls": [
                "Konnte ich Sie mit dieser Bewerbung überzeugen? Ich bin für einen Einstieg zum nächstmöglichen Zeitpunkt verfügbar. Einen vertiefenden Eindruck gebe ich Ihnen gerne in einem persönlichen Gespräch. Ich freue mich über Ihre Einladung!",
                "Ich danke Ihnen für das Interesse an meiner Bewerbung. Zum nächstmöglichen Zeitpunkt bin ich verfügbar. Wenn Sie mehr von mir erfahren möchten, freue ich mich über eine Einladung zum Vorstellungsgespräch.",
                "Ich hoffe, dass Sie einen ersten Eindruck von mir gewinnen konnten. Ein Einstieg zum nächstmöglichen Zeitpunkt ist für mich möglich. Ich freue mich, weitere Details und offene Fragen in einem persönlichen Gespräch auszutauschen."
            ]
        }
    }';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = app()->user->applications()->paginate(14);

        return view("bewerbungen.applications.index", compact("applications"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company)
    {
        $application = app()->user->applications()->create([
            "company_id" => $company->id,
            /*"body" => '{
                "greeting": {
                    "body": ""
                },
                "awareofyou": {
                    "body": ""
                },
                "currentactivity": {
                    "body": ""
                },
                "whycontact": {
                    "body": ""
                },
                "wayOfWork": [],
                "skills": [],
                "ending": {
                    "body": ""
                }
            }',*/
        ]);

        return redirect()->route("bewerbungen.applications.edit", $application)->with("status", "Eine leere Bewerbung für {$company->name} wurde erfolgreich angelegt.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(PdfRequest $request, Application $application)
    {
        $filename = storage_path('app/public/bewerbungen/templates.json');

        $content = json_decode($application->body, true);
        $templates = json_decode(file_get_contents($filename), true);
        $help = app()->user->resume;
        $resume = json_decode($help->data);
        if (is_null($help->signature)) {
            $signature = base64_encode(file_get_contents($request->file('signature')));
        }
        else {
            $signature = base64_encode($help->signature);
        }
        /*$signature = app()->user->resume
            ? base64_encode(app()->user->resume->signature)
            : base64_encode(file_get_contents($request->file('signature')));*/
        $res = [];
        $format = $request->all();

        foreach($content as $key => $val) {
            $res[$templates[$key]['number']] = $content[$key];
            if (is_array($val) && isset($val['keywords'])) {
                //$content[$key] = $this->helpKeywordSection($content[$key], $templates[$key]);
                $res[$templates[$key]['number']] = $this->helpKeywordSection($content[$key], $templates[$key]);
            }
        }

        ksort($res);

        $pdf = new Mpdf([
            'tempDir' => sys_get_temp_dir(),
            'default_font_size' => $request->textgroesse,
            'default_font' => 'opensans',
        ]);
        $pdf->DefHTMLFooterByName('footer',
            '<table style="width: 100%; border: none; border-top: 1px solid black;">
    <tr style="border: none;">
        <td style="border:none; text-align: right;">{PAGENO}/{nbpg}</td>
    </tr>
</table>');
        $pdf->WriteHTML(view("bewerbungen.applications.pdf", compact("application", "res", "resume", "format", "signature"))->render());
        $pdf->Output();
    }

    private function helpKeywordSection($section, $tpl) {
        $text = "";
        foreach($tpl['text'] as $i => $t) {
            $text = $text . $t . " ";
            if (isset($section['values'][$i])) {
                for ($j = 0; $j < count($section['values'][$i]); $j++) {
                    $text = $text . $section['values'][$i][$j];
                    if ($j < count($section['values'][$i]) - 2) {
                        $text = $text . ", ";
                    }
                    if ($j === count($section['values'][$i]) - 2) {
                        $text = $text . " " . $tpl['keywords'][$i]['conjunction'] . " ";
                    }
                }
                $text .= " ";
            }
        }

        return $text;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        $user = $application->user;
        $resume = $user->resume;
        if (!(is_null($resume) || is_null($resume->signature))) {
            $signature = base64_encode($resume->signature);
        }
        else{
            $signature = false;
        }
        
        return view("bewerbungen.applications.edit", compact("application", "signature"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        $attributes = $request->validate([
            "body" => "required"
        ]);

        $application->update([
            "body" => json_encode($request->input("body"))
        ]);

        return redirect()->route("bewerbungen.applications.edit", $application)->with("status", "Die Änderungen wurden erfolgreich gespeichert");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        if ($application->user->id !== app()->user->id && !app()->user->isAdmin()) {
            return abort(403);
        }

        $application->delete();

        return redirect()->route("bewerbungen.applications.index")->with("status", "Die Bewerbung für {$application->company->name} wurde erfolgreich gelöscht.");
    }

    /**
     * Gibt die Templates bzw. Textbausteine für ein Bewerbungsanschreiben in JSON-Format aus.
     *
     * @return false|string
     */
    public function templates() {
        $filename = storage_path('app/public/bewerbungen/templates.json');
        $templates = file_get_contents($filename);
        if (!$templates) {
            file_put_contents($filename, self::STANDARD_TEMPLATES);
            $templates = self::STANDARD_TEMPLATES;
        }

        return $templates;
    }
}
