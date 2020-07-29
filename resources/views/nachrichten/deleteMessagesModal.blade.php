{{-- Modales Fenster: Bestätigung des Löschens der ausgewählten Nachrichten --}}

<div class="modal fade" id="deleteMessagesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nachrichten löschen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Sind Sie sicher, dass Sie die ausgewählten Nachrichten löschen möchten?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                {{-- Formular zum Löschen des Änderungsverlaufs --}}
                <form class="form" id="formDeleteMessages" method="post" action="{{ route('user.nachrichten.delete_many') }}">
                    @csrf
                    {{-- Als Post, da das mit DELETE nicht funktioniert. (Ich glaube, DELETE erwartet genau eine Ressource --}}
                    <input type="submit" class="btn btn-danger" value="Löschen"/>
                </form>
            </div>
        </div>
    </div>
</div>
