{{-- Modales Fenster: Bestätigung des Löschens des Änderungsverlaufs --}}

<div class="modal fade" id="clearHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verlauf löschen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Sind Sie sicher, dass Sie den Änderungsverlauf löschen möchen?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                {{-- Formular zum Löschen des Änderungsverlaufs --}}
                <form class="form" method="post" action="{{ route($route, $project) }}">
                    @csrf
                    @method('delete')
                    <input class="btn btn-danger" type="submit" value="Verlauf löschen"/>
                </form>
            </div>
        </div>
    </div>
</div>
