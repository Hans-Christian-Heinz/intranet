{{----}}

<div class="modal fade" id="deleteImage{{ $index }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bilddatei löschen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Sind Sie sicher, dass Sie diese Bilddatei löschen möchten?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" action="{{ route('abschlussprojekt.bilder.delete', $project) }}" method="POST">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="datei" value="{{ $file }}"/>
                    <button type="submit" class="btn btn-primary">Löschen</button>
                </form>
            </div>
        </div>
    </div>
</div>
