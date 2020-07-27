<?php


namespace App\Traits;


use App\Documentation;
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
}
