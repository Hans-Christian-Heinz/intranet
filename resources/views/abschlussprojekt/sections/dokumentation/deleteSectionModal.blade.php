{{-- Modales Fenster zum Löschen eines Abschnitts --}}

<div class="modal fade" id="deleteSection{{ $section->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Abschnitt löschen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Sind Sie sicher, dass Sie diesen Abschnitt (einschließlich aller Unterabschnitte) löschen möchten?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" method="POST"
                      action="{{ route('abschlussprojekt.dokumentation.abschnitte.delete',
                                ['project' => $documentation->project, 'section' => $section,]) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Abschnitt löschen</button>
                </form>
            </div>
        </div>
    </div>
</div>
