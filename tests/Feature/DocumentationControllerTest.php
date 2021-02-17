<?php

namespace Tests\Feature;

use App\Documentation;
use App\User;
use App\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class DocumentationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_documentation()
    {
        $user = factory(User::class)->make();
        $user->save();
        $user->project()->create();
        $user->save();
        $project = $user->project;
        app()->user = $user;

        $response = $this->actingAs($user)->get('/abschlussprojekt/' . $project->id . '/dokumentation/create');
        $response->assertRedirect('/abschlussprojekt/' . $project->id . '/dokumentation');
        $this->assertNotNull($project->documentation()->get());
    }

    public function test_store_documentation()
    {
        $user = factory(User::class)->make();
        $user->save();
        $user->project()->create();
        $user->save();
        $project = $user->project;
        //app()->user = $user;
        //$this->actingAs($user)->get('/abschlussprojekt/' . $project->id . '/antrag/create');
        $documentation = new Documentation();
        $documentation->project()->associate($project);
        $documentation->lockedBy()->associate($user);
        $documentation->save();
        $project->documentation()->associate($documentation);
        $project->save();
        $version = new Version();
        $version->user()->associate($user);
        $documentation->versions()->save($version);
        $documentation->makeSections(Documentation::SECTIONS, $version);

        app()->user = $user;
        $response = $this->actingAs(new LdapUser())->post('/abschlussprojekt/' . $project->id . '/dokumentation', [
            "abbreviations" => "[{\"Abkürzung\":\"sql\",\"Ausgeschrieben\":\"Structured Query Language\",\"number\":0}]",
            "shortTitle" => "title",
            "longTitle" => "longTitle",
            "umfeld" => "test",
            "ziele" => "test",
            "begruendung" => "test",
            "schnittstellen" => "test",
            "abgrenzung" => "test",
            "abweichungen" => "test",
            "hardware" => "[{\"Name\":\"ressource\",\"Beschreibung\":\"Beschreibung\",\"Kosten\":\"100\",\"number\":0}]",
            "software" => "[{\"Name\":\"ressource\",\"Beschreibung\":\"Beschreibung\",\"Kosten\":\"100\",\"number\":0}]",
            "personal" => "[{\"Name\":\"ressource\",\"Beschreibung\":\"Beschreibung\",\"Kosten\":\"100\",\"number\":0}]",
            "entwicklungsprozess" => "test",
            "ist_analyse" => "test",
            "make_buy" => "test",
            "kosten" => "test",
            "amortisation" => "test",
            "nutzwertanalyse" => "test",
            "anwendungsfaelle" => "test",
            "quality" => "test",
            "lastenheft" => "test",
            "plattform" => "test",
            "architektur" => "test",
            "user_interface" => "test",
            "datenmodell" => "test",
            "geschaeftslogik" => "test",
            "qualitaetssicherung" => "test",
            "pflichtenheft" => "test",
            "datenstrukturen" => "test",
            "benutzeroberfl" => "test",
            "impl_geschaeftslogik" => "test",
            "abnahme_phase" => "test",
            "einfuehrung" => "test",
            "dokumentation" => "test",
            "soll_ist_vgl" => "test",
            "planung" => 1,
            "entwurf" => 1,
            "implementierung" => 1,
            "test" => 1,
            "abnahme" => 1,
            "lessons" => "test",
            "ausblick" => "test",
            "gantt" => "test",
            "lasten" => "test",
            "usecase" => "test",
            "pflichten" => "test",
            "db_modell" => "test",
            "klassendiagramm" => "test",
            "ereignis_prozess" => "test",
            "oberfl_entwurf" => "test",
            "screenshots" => "test",
            "user_doku" => "test",
            "developer_doku" => "test",
            "testfall" => "test",
            "code_auszug" => "test",
        ]);
        $response->assertRedirect('/');

        $documentation = $project->documentation()->first();
        $version = $documentation->versions()->orderBy('id', 'DESC')->first();
        // $sections = $version->sections->map(function($section) {
        $sections = $documentation->getAllSections($version)->map(function($section) {
            return [
                "name" => $section->name,
                "heading" => $section->heading,
                "text" => $section->text,
            ];
        })->all();
        $expected = [
            [
                "name" => "abbreviations",
                "heading" => "Abkürzungsverzeichnis",
                "text" => "[{\"Abkürzung\":\"sql\",\"Ausgeschrieben\":\"Structured Query Language\",\"number\":0}]",
            ],
            [
                "name" => "title",
                "heading" => "Titel",
                "text" => "title||longTitle",
            ],
            [
                "name" => "intro",
                "heading" => "Einleitung",
                "text" => null,
            ],
            [
                "name" => "projekt_planung",
                "heading" => "Projektplanung",
                "text" => null,
            ],
            [
                "name" => "analyse",
                "heading" => "Analysephase",
                "text" => null,
            ],
            [
                "name" => "entwurf_phase",
                "heading" => "Entwurfphase",
                "text" => null,
            ],
            [
                "name" => "impl_phase",
                "heading" => "Implementierungsphase",
                "text" => null,
            ],
            [
                "name" => "abnahme_phase",
                "heading" => "Abnahmephase",
                "text" => "test",
            ],
            [
                "name" => "einfuehrung",
                "heading" => "Einführungsphase",
                "text" => "test",
            ],
            [
                "name" => "dokumentation",
                "heading" => "Dokumentation",
                "text" => "test",
            ],
            [
                "name" => "fazit",
                "heading" => "Fazit",
                "text" => null,
            ],
            [
                "name" => "anhang",
                "heading" => "Anhang",
                "text" => null,
            ],
            [
                "name" => "umfeld",
                "heading" => "Projektumfeld",
                "text" => "test",
            ],
            [
                "name" => "ziele",
                "heading" => "Projektziele",
                "text" => "test",
            ],
            [
                "name" => "begruendung",
                "heading" => "Projektbegründung",
                "text" => "test",
            ],
            [
                "name" => "schnittstellen",
                "heading" => "Prozessschnittstellen",
                "text" => "test",
            ],
            [
                "name" => "abgrenzung",
                "heading" => "Projektabgrenzung",
                "text" => "test",
            ],
            [
                "name" => "doku_phasen",
                "heading" => "Projektphasen",
                "text" => null,
            ],
            [
                "name" => "abweichungen",
                "heading" => "Abweichnungen vom Projektantrag",
                "text" => "test",
            ],
            [
                "name" => "ressourcen",
                "heading" => "Ressourcenplanung",
                "text" => null,
            ],
            [
                "name" => "entwicklungsprozess",
                "heading" => "Entwicklungsprozess",
                "text" => "test",
            ],
            [
                "name" => "hardware",
                "heading" => "Hardware",
                "text" => "[{\"Name\":\"ressource\",\"Beschreibung\":\"Beschreibung\",\"Kosten\":\"100\",\"number\":0}]",
            ],
            [
                "name" => "software",
                "heading" => "Software",
                "text" => "[{\"Name\":\"ressource\",\"Beschreibung\":\"Beschreibung\",\"Kosten\":\"100\",\"number\":0}]",
            ],
            [
                "name" => "personal",
                "heading" => "Personal",
                "text" => "[{\"Name\":\"ressource\",\"Beschreibung\":\"Beschreibung\",\"Kosten\":\"100\",\"number\":0}]",
            ],
            [
                "name" => "gesamt",
                "heading" => "Gesamtkosten",
                "text" => null,
            ],
            [
                "name" => "ist_analyse",
                "heading" => "Ist-Analyse",
                "text" => "test",
            ],
            [
                "name" => "wirtschaft_analyse",
                "heading" => "Wirtschaftlichkeitsanalyse",
                "text" => null,
            ],
            [
                "name" => "nutzwertanalyse",
                "heading" => "Nutzwertanalyse",
                "text" => "test",
            ],
            [
                "name" => "anwendungsfaelle",
                "heading" => "Anwendungsfälle",
                "text" => "test",
            ],
            [
                "name" => "quality",
                "heading" => "Qualitätsanforderungen",
                "text" => "test",
            ],
            [
                "name" => "lastenheft",
                "heading" => "Lastenheft/Fachkonzept",
                "text" => "test",
            ],
            [
                "name" => "make_buy",
                "heading" => "Make or Buy-Entscheidung",
                "text" => "test",
            ],
            [
                "name" => "kosten",
                "heading" => "Projektkosten",
                "text" => "test",
            ],
            [
                "name" => "amortisation",
                "heading" => "Amortisationsdauer",
                "text" => "test",
            ],
            [
                "name" => "plattform",
                "heading" => "Zielplattform",
                "text" => "test",
            ],
            [
                "name" => "architektur",
                "heading" => "Architekturdesign",
                "text" => "test",
            ],
            [
                "name" => "user_interface",
                "heading" => "Entwurf der Benutzeroberfläche",
                "text" => "test",
            ],
            [
                "name" => "datenmodell",
                "heading" => "Datenmodell",
                "text" => "test",
            ],
            [
                "name" => "geschaeftslogik",
                "heading" => "Geschäftslogik",
                "text" => "test",
            ],
            [
                "name" => "qualitaetssicherung",
                "heading" => "Maßnahmen zur Qualitätssicherung",
                "text" => "test",
            ],
            [
                "name" => "pflichtenheft",
                "heading" => "Pflichtenheft",
                "text" => "test",
            ],
            [
                "name" => "datenstrukturen",
                "heading" => "Implementierung der Datenstrukturen",
                "text" => "test",
            ],
            [
                "name" => "benutzeroberfl",
                "heading" => "Implementierung der Benutzeroberfläche",
                "text" => "test",
            ],
            [
                "name" => "impl_geschaeftslogik",
                "heading" => "Implementierung der Geschäftslogik",
                "text" => "test",
            ],
            [
                "name" => "soll_ist_vgl",
                "heading" => "Soll-Ist-Vergleich",
                "text" => "test##TEXTEND##1;1;1;1;1;",
            ],
            [
                "name" => "lessons",
                "heading" => "Lessons Learned",
                "text" => "test",
            ],
            [
                "name" => "ausblick",
                "heading" => "Ausblick",
                "text" => "test",
            ],
            [
                "name" => "gantt",
                "heading" => "Detaillierte Zeitplanung",
                "text" => "test",
            ],
            [
                "name" => "lasten",
                "heading" => "Lastenheft (Auszug)",
                "text" => "test",
            ],
            [
                "name" => "usecase",
                "heading" => "Use-Case-Diagramm",
                "text" => "test",
            ],
            [
                "name" => "pflichten",
                "heading" => "Pflichtenheft (Auszug)",
                "text" => "test",
            ],
            [
                "name" => "db_modell",
                "heading" => "Datenbankmodell",
                "text" => "test",
            ],
            [
                "name" => "klassendiagramm",
                "heading" => "Klassendiagramm",
                "text" => "test",
            ],
            [
                "name" => "ereignis_prozess",
                "heading" => "Ereignisgesteuerte Prozesskette",
                "text" => "test",
            ],
            [
                "name" => "oberfl_entwurf",
                "heading" => "Oberflächenentwürfe",
                "text" => "test",
            ],
            [
                "name" => "screenshots",
                "heading" => "Screenshots der Anwendung",
                "text" => "test",
            ],
            [
                "name" => "user_doku",
                "heading" => "Benutzerdokumentation (Auszug)",
                "text" => "test",
            ],
            [
                "name" => "developer_doku",
                "heading" => "Entwicklerdokumentation (Auszug)",
                "text" => "test",
            ],
            [
                "name" => "testfall",
                "heading" => "Testfall und Konsolenaufruf",
                "text" => "test",
            ],
            [
                "name" => "code_auszug",
                "heading" => "Code-Auszug",
                "text" => "test",
            ],
        ];
        $this->assertEquals($sections, $expected);
    }
}
