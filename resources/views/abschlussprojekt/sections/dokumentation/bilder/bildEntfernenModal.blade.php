{{-- Modales Fenster: Bestätigen des Löschens eines Datensatzes in images --}}

<div class="modal fade" id="bildEntfernen{{ $image->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bild entfernen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Sind Sie sicher, dass Sie dieses Bild aus diesem Abschnitt entfernen möchten?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" action="{{ route('abschlussprojekt.dokumentation.images.detach', $documentation->project) }}" method="POST">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="img_id" value="{{ $image->id }}"/>
                    <input type="hidden" name="section_id" value="{{ $s->id }}"/>
                    <button type="submit" class="btn btn-danger">Bild entfernen</button>
                </form>
            </div>
        </div>
    </div>
</div>
