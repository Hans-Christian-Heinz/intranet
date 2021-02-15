<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Helpers\General\IncrementCounter;
use App\Http\Requests\ChooseSupervisorRequest;
use App\Project;
use App\Proposal;
use Illuminate\Http\Request;
use App\User;

class AdminProjectController extends Controller
{
    /**
     * Zeige eine Liste aller Abschlussprojekte, aufgeteilt nach Abschlussjahr
     */
    public function index() {
        $all_projects = Project::with('supervisor')->get()->sortBy(function ($value) {
            return $value->user->full_name;
        });
        $all_projects = $all_projects->reject(function($project) {
            return $project->user->isAdmin;
        });
        $projects = [];
        //Teile die Projekte nach dem Abschlussjahr auf.
        foreach($all_projects as $p) {
            $jahr = (string)$p->pruefungs_jahr;
            if (! array_key_exists($jahr, $projects)) {
                $projects[$jahr] = ['Anwendungsentwicklung' => collect(), 'Systemintegration' => collect()];
            }
            $fachrichtung = $p->user->fachrichtung;
            //Sollte eigentlich nicht vorkommen
            if ($fachrichtung != 'Ausbilder') {
                $projects[$jahr][$fachrichtung]->push($p);
            }
        }

        ksort($projects);

        $admins = User::where('fachrichtung', 'Ausbilder')->get();

        return view('admin.abschlussprojekt.index', compact('projects', 'admins'));
    }

    public function betreuer(ChooseSupervisorRequest $request, Project $project) {
        $project->supervisor()->associate(User::find($request->supervisor_id));
        $project->save();

        return redirect()->back()->with('status', 'Dem Projekt von ' . $project->user->full_name
            . ' wurde ein neuer Betreuer zugeteilt.');
    }

    public function previewProposal(Request $request, Project $project, Proposal $proposal) {
        $title = 'Projektantrag ' . $project->user->full_name;

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',

            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,

            'fontDir' => array_merge($fontDirs, [base_path() . '/resources/fonts']),
            'fontdata' => $fontData + [
                    'opensans' => [
                        'R' => 'OpenSans-Regular.ttf',
                        'B' => 'OpenSans-Bold.ttf'
                    ]
                ],
            'default_font_size' => 10,
            'default_font' => 'opensans',

            'tempDir' => sys_get_temp_dir(),
        ]);

        $mpdf->DefHTMLFooterByName('footer',
            '<table style="width: 100%; border: none; border-top: 1px solid black;">
    <tr style="border: none;">
        <td style="border: none;">' . $project->user->full_name . '</td>
        <td style="border:none; text-align: right;">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

        $mpdf->SetTitle($title);

        $mpdf->WriteHTML(view('pdf.antrag', [
            'project' => $proposal->project()->with('user')->with('supervisor')->first(),
            'proposal' => $proposal,
            'format' => [
                "textfarbe" => "#000000",
                "textart" => "times",
                "textgroesse" => 10,
                "ueberschrFarbe" => "#336699",
                "kopfHintergrund" => "#336699",
                "kopfText" => "#ffffff",
                "koerperBackground" => "#d3d3d3",
                "koerperHintergrund" => "#ffffff",
                "koerperText" => "#000000",
            ],
            'version' => $proposal->latestVersion(),
            'table_nr' => new IncrementCounter(),
            'inhalt_counter' => new IncrementCounter(),
        ])->render());

        return $mpdf->Output($title . '.pdf', 'I');
    }

    public function previewDocumentation(Request $request, Project $project, Documentation $documentation) {
        $version = $documentation->latestVersion();

        $title = 'Projektdokumentation ' . $project->user->full_name;

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',

            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,

            'setAutoTopMargin' => 'stretch',
            'autoMarginPadding' => 5,

            'fontDir' => array_merge($fontDirs, [base_path() . '/resources/fonts']),
            'fontdata' => $fontData + [
                    'opensans' => [
                        'R' => 'OpenSans-Regular.ttf',
                        'B' => 'OpenSans-Bold.ttf'
                    ]
                ],
            'default_font_size' => 10,
            'default_font' => 'opensans',

            'tempDir' => sys_get_temp_dir(),
        ]);

        $mpdf->DefHTMLHeaderByName('header',
            '<div style="border-bottom: 1px solid black;"><b>' .
            $documentation->shortTitle . '</b><br/>' . $documentation->longTitle .
            '</div>');

        $mpdf->DefHTMLFooterByName('footer',
            '<table style="width: 100%; border: none; border-top: 1px solid black;">
    <tr style="border: none;">
        <td style="border: none;">' . $project->user->full_name . '</td>
        <td style="border:none; text-align: right;">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

        $mpdf->SetTitle($title);

        $mpdf->WriteHTML(view('pdf.dokumentation', [
            'project' => $documentation->project()->with('user')->with('supervisor')->first(),
            'documentation' => $documentation,
            'format' => [
                "textfarbe" => "#000000",
                "textart" => "times",
                "textgroesse" => 10,
                "ueberschrFarbe" => "#336699",
                "kopfHintergrund" => "#336699",
                "kopfText" => "#ffffff",
                "koerperBackground" => "#d3d3d3",
                "koerperHintergrund" => "#ffffff",
                "koerperText" => "#000000",
            ],
            'version' => $version,
            'kostenstellen' => $documentation->getKostenstellen($version),
            'kostenstellen_gesamt' => $documentation->getKostenstellenGesamt($version),
            'zeitplanung' => $documentation->zeitplanung,
            'table_nr' => new IncrementCounter(),
            'image_nr' => new IncrementCounter(),
            'inhalt_counter' => new IncrementCounter(),
        ])->render());

        return $mpdf->Output($title . '.pdf', 'I');
    }
}
