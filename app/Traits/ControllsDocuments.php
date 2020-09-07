<?php


namespace App\Traits;


use App\Documentation;
use App\Http\Requests\PdfRequest;
use App\Proposal;

trait ControllsDocuments
{
    /**
     * Sperre ein Dokument für andere Benutzer, bevor es bearbeitet werden kann
     *
     * @param Proposal|Documentation $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lockDocument($document) {
        if (is_null($document->lockedBy)) {
            $document->lockedBy()->associate(app()->user);
            $document->save();

            return redirect()->back()
                ->with('status', 'Das Dokument wurde für andere Benutzer gesperrt. Sie können es nun bearbeiten.');
        }
        else {
            return redirect()->back()
                ->with('danger', 'Das Dokument ist für Sie gesperrt. Es wird momentan von einem anderen Benutzer bearbeitet.');
        }
    }

    /**
     * Gebe ein Dokument für andere Benutzer frei, nachdem es bearbeitet worden war.
     *
     * @param Proposal|Documentation $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function releaseDocument($document) {
        if (app()->user->is($document->lockedBy)) {
            $document->lockedBy()->associate(null);
            $document->save();

            return redirect()->back()
                ->with('status', 'Das Dokument wurde für freigegeben. Sie können es nicht mehr bearbeiten.');
        }
        else {
            return redirect()->back()
                ->with('danger', 'Das Dokument ist nicht von Ihnen gesperrt. Sie können es auch nicht freigeben.');
        }
    }

    private function vorschau_pdf(PdfRequest $request) {
        $title = 'Vorschau PDF-Formatierung';

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
            'default_font_size' => $request->textgroesse,
            'default_font' => 'opensans',

            'tempDir' => sys_get_temp_dir(),
        ]);

        $mpdf->DefHTMLFooterByName('footer',
            '<table style="width: 100%; border: none; border-top: 1px solid black;">
    <tr style="border: none;">
        <td style="border: none;">Fusßnote</td>
        <td style="border:none; text-align: right;">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

        $mpdf->SetTitle($title);

        $mpdf->WriteHTML(view('pdf.vorschau', [
            'format' => $request->all(),
        ])->render());

        return $mpdf->Output($title . '.pdf', 'I');
    }
}
