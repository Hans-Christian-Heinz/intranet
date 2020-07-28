{{-- Modales Fenster zum Sperren oder Freigeben Abschnitts --}}

<div class="modal fade section-modal" id="lockSection{{ $section->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Abschnitt {{ $section->is_locked ? 'freigeben' : 'sperren' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($section->is_locked)
                    Sind Sie sicher, dass Sie den Abschnitt freigeben möchten? (Änderungen am Abschnitt würden somit wieder ermöglicht.)
                @else
                    Sind Sie sicher, dass Sie den Abschnitt sperren möchten? (Um Änderungen am Abschnitt zu verhindern,
                    wird auch das Wiederherstellen alter Versionen dieses Dokuments verhindert.)
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" method="POST"
                      action="{{ route('abschlussprojekt.sections.lock', ['project' => $section->getParent()->project, 'section' => $section,]) }}">
                    @csrf
                    <input type="hidden" name="section_id" value="{{ $section ? $section->id : 0 }}">
                    <button type="submit" class="btn btn-primary">{{ $section->is_locked ? 'Abschnitt freigeben' : 'Abschnitt sperren' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
