<?php

namespace App\Http\Controllers;

use App\Application;
use App\Company;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ApplicationController extends Controller
{
    const STANDARD_TEMPLATES = '{
        "greeting": {
            "heading" : "Anrede",
            "fix": true,
            "tpls": [
                "Sehr geehrte Damen und Herren,",
                "Sehr geehrte Frau Musterfrau,",
                "Sehr geehrter Herr Mustermann,"
            ]
        },
        "awareofyou": {
            "heading": "Wie bist du auf diese Stelle aufmerksam geworden?",
            "fix": false,
            "tpls": [
                "mit großem Interesse bin ich im XING Stellenmarkt auf die ausgeschriebene Position aufmerksam geworden. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).",
                "auf der Suche nach einer neuen Beschäftigung bin ich auf Ihr Unternehmen aufmerksam geworden und komme jetzt initiativ auf Sie zu. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).",
                "wie telefonisch besprochen, interessiere ich mich sehr für eine Beschäftigung in Ihrem Unternehmen. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w)."
            ]
        },
        "currentactivity": {
            "heading": "Was ist deine derzeitige Beschäftigung?",
            "fix": false,
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
            "tpls": [
                "Ihr Stellenangebot hört sich toll an! Ich hoffe, mir hierdurch persönliche und fachliche Entwicklungsmöglichkeiten erschließen zu können. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                "Nachdem ich schon länger in diesem Bereich tätig bin, suche ich jetzt nach einer neuen Position, in der ich mehr Verantwortung übernehmen kann. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                "Mein Wunsch ist es, die beschriebene Aufgabenstellung als nächsten Schritt für meine weitere berufliche Entwicklung in Ihrem Hause zu nutzen. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                "Ich suche an meinem neuen Wohnort eine interessante Beschäftigung und bin daher auf Ihr Unternehmen aufmerksam geworden. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen."
            ]
        },
        "wayofwork": {
            "heading": "Was zeichnet deine Arbeitsweise aus?",
            "fix": false,
            "checkbox": true,
            "tpls": ["zuverlässig", "verantwortungsbewusst", "präzise"]
        },
        "skills": {
            "heading": "Welche Begriffe beschreiben am besten deine persönlichen Kompetenzen?",
            "fix": false,
            "checkbox": true,
            "tpls": ["flexibel", "motiviert", "teamorientiert"]
        },
        "ending": {
            "heading": "Schlusswort",
            "fix": true,
            "tpls": [
                "Konnte ich Sie mit dieser Bewerbung überzeugen? Ich bin für einen Einstieg zum nächstmöglichen Zeitpunkt verfügbar. Einen vertiefenden Eindruck gebe ich Ihnen gerne in einem persönlichen Gespräch. Ich freue mich über Ihre Einladung!",
                "Ich danke Ihnen für das Interesse an meiner Bewerbung. Zum nächstmöglichen Zeitpunkt bin ich verfügbar. Wenn Sie mehr von mir erfahren möchten, freue ich mich über eine Einladung zum Vorstellungsgespräch.",
                "Ich hoffe, dass Sie einen ersten Eindruck von mir gewinnen konnten. Ein Einstieg zum nächstmöglichen Zeitpunkt ist für mich möglich. Ich freue mich, weitere Details und offene Fragen in einem persönlichen Gespräch auszutauschen."
            ]
        }
    }';
    //Standardtemplates für Bewerbungsanschreiben. Wird verwendet, wenn die Datei storage/app/public/bewerbungen/templates.json nicht gefunden wird
    /*const STANDARD_TEMPLATES = '{
        "greeting": {
            "first": "Sehr geehrte Damen und Herren,",
            "second": "Sehr geehrte Frau Musterfrau,",
            "third": "Sehr geehrter Herr Mustermann,"
        },
        "awareofyou": {
            "first": "mit großem Interesse bin ich im XING Stellenmarkt auf die ausgeschriebene Position aufmerksam geworden. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).",
            "second": "auf der Suche nach einer neuen Beschäftigung bin ich auf Ihr Unternehmen aufmerksam geworden und komme jetzt initiativ auf Sie zu. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).",
            "third": "wie telefonisch besprochen, interessiere ich mich sehr für eine Beschäftigung in Ihrem Unternehmen. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w)."
        },
        "currentactivity": {
            "first": "Zurzeit arbeite ich als Musterberuf bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
            "second": "Zurzeit studiere ich Musterstudiengang an der Musterhochschule. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
            "third": "Zurzeit befinde ich mich in der Ausbildung als Musterausbildung bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
            "fourth": "Zurzeit absolviere ich ein Praktikum im Bereich Musterstelle bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
            "fifth": "Zurzeit besuche ich die Musterschule in Musterort. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen."
        },
        "whycontact": {
            "first": "Ihr Stellenangebot hört sich toll an! Ich hoffe, mir hierdurch persönliche und fachliche Entwicklungsmöglichkeiten erschließen zu können. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
            "second": "Nachdem ich schon länger in diesem Bereich tätig bin, suche ich jetzt nach einer neuen Position, in der ich mehr Verantwortung übernehmen kann. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
            "third": "Mein Wunsch ist es, die beschriebene Aufgabenstellung als nächsten Schritt für meine weitere berufliche Entwicklung in Ihrem Hause zu nutzen. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
            "fourth": "Ich suche an meinem neuen Wohnort eine interessante Beschäftigung und bin daher auf Ihr Unternehmen aufmerksam geworden. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen."
        },
        "wayOfWork": ["zuverlässig", "verantwortungsbewusst", "präzise"],
        "skills": ["flexibel", "motiviert", "teamorientiert"],
        "ending": {
            "first": "Konnte ich Sie mit dieser Bewerbung überzeugen? Ich bin für einen Einstieg zum nächstmöglichen Zeitpunkt verfügbar. Einen vertiefenden Eindruck gebe ich Ihnen gerne in einem persönlichen Gespräch. Ich freue mich über Ihre Einladung!",
            "second": "Ich danke Ihnen für das Interesse an meiner Bewerbung. Zum nächstmöglichen Zeitpunkt bin ich verfügbar. Wenn Sie mehr von mir erfahren möchten, freue ich mich über eine Einladung zum Vorstellungsgespräch.",
            "third": "Ich hoffe, dass Sie einen ersten Eindruck von mir gewinnen konnten. Ein Einstieg zum nächstmöglichen Zeitpunkt ist für mich möglich. Ich freue mich, weitere Details und offene Fragen in einem persönlichen Gespräch auszutauschen."
        }
    }';*/

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
            "body" => '{
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
            }'
        ]);

        return redirect()->route("bewerbungen.applications.edit", $application)->with("status", "Eine leere Bewerbung für {$company->name} wurde erfolgreich angelegt.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        $content = json_decode($application->body);
        $resume = json_decode(app()->user->resume->data);

        $pdf = new Mpdf([
            'tempDir' => sys_get_temp_dir(),
        ]);
        $pdf->WriteHTML(view("bewerbungen.applications.pdf", compact("application", "content", "resume"))->render());
        $pdf->Output();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        return view("bewerbungen.applications.edit", compact("application"));
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
