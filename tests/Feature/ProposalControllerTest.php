<?php

namespace Tests\Feature;

use App\Proposal;
use App\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class ProposalControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_proposal()
    {
        $user = factory(User::class)->make();
        $user->save();
        $user->project()->create();
        $user->save();
        $project = $user->project;
        app()->user = $user;

        $response = $this->actingAs($user)->get('/abschlussprojekt/' . $project->id . '/antrag/create');
        $response->assertRedirect('/abschlussprojekt/' . $project->id . '/antrag');
        $this->assertNotNull($project->proposal()->get());
    }

    public function test_store_proposal()
    {
        $user = factory(User::class)->make();
        $user->save();
        $user->project()->create();
        $user->save();
        $project = $user->project;
        //app()->user = $user;
        //$this->actingAs($user)->get('/abschlussprojekt/' . $project->id . '/antrag/create');
        $proposal = new Proposal();
        $proposal->project()->associate($project);
        $proposal->lockedBy()->associate($user);
        $proposal->save();
        $project->proposal()->associate($proposal);
        $project->save();
        $version = new Version();
        $version->user()->associate($user);
        $proposal->versions()->save($version);
        $proposal->makeSections(Proposal::SECTIONS, $version);

        app()->user = $user;
        $response = $this->actingAs(new LdapUser())->post('/abschlussprojekt/' . $project->id . '/antrag', [
            "topic" => "test",
            "start" => "2050-01-01",
            "end" => "2051-01-01",
            "description" => "test",
            "environment" => "test",
            "planung" => '[{"Phasenname": "test", "Dauer": 1}]',
            "entwurf" => '[{"Phasenname": "test", "Dauer": 1}]',
            "implementierung" => '[{"Phasenname": "test", "Dauer": 1}]',
            "test" => '[{"Phasenname": "test", "Dauer": 1}]',
            "abnahme" => '[{"Phasenname": "test", "Dauer": 1}]',
            "documentation" => "test",
            "attachments" => "test",
            "presentation" => "test",
        ]);
        $response->assertRedirect('/');

        $proposal = $project->proposal()->first();
        $version = $proposal->versions()->orderBy('id', 'DESC')->first();
        // $sections = $version->sections->map(function($section) {
        $sections = $proposal->getAllSections($version)->map(function($section) {
            return [
                "name" => $section->name,
                "heading" => $section->heading,
                "text" => $section->text,
            ];
        })->all();
        $expected = [
            [
                "name" => "topic",
                "heading" => "Thema",
                "text" => "test",
            ],
            [
                "name" => "deadline",
                "heading" => "Termin",
                "text" => "2050-01-01||2051-01-01",
            ],
            [
                "name" => "description",
                "heading" => "Beschreibung",
                "text" => "test",
            ],
            [
                "name" => "environment",
                "heading" => "Umfeld",
                "text" => "test",
            ],
            [
                "name" => "phases",
                "heading" => "Projektphasen mit Zeitplanung",
                "text" => null,
            ],
            [
                "name" => "documentation",
                "heading" => "Dokumentation",
                "text" => "test",
            ],
            [
                "name" => "attachments",
                "heading" => "Anlagen",
                "text" => "test",
            ],
            [
                "name" => "presentation",
                "heading" => "Präsentationsmittel",
                "text" => "test",
            ],
            [
                "name" => "planung",
                "heading" => "Planung und Analyse",
                "text" => "[{\"Phasenname\": \"test\", \"Dauer\": 1}]",
            ],
            [
                "name" => "entwurf",
                "heading" => "Entwurf",
                "text" => "[{\"Phasenname\": \"test\", \"Dauer\": 1}]",
            ],
            [
                "name" => "implementierung",
                "heading" => "Implementierung",
                "text" => "[{\"Phasenname\": \"test\", \"Dauer\": 1}]",
            ],
            [
                "name" => "test",
                "heading" => "Test",
                "text" => "[{\"Phasenname\": \"test\", \"Dauer\": 1}]",
            ],
            [
                "name" => "abnahme",
                "heading" => "Abnahme und Dokumentation",
                "text" => "[{\"Phasenname\": \"test\", \"Dauer\": 1}]",
            ],
        ];
        $this->assertEquals($sections, $expected);
    }
}
