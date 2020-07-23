{{-- Modales Fenster zum Hinzufügen eines Unterabschnitts --}}

<div class="modal fade section-modal" id="addSection{{ $section->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unterabschnitt hinzufügen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-justify">
                    Beachten Sie, dass der Variablenname innerhalb des Dokuments eindeutig sein muss.<br/>
                    Die Option "Template" existiert hauptsächlich, falls versehentlich ein nicht Standard-Abschnitt
                    gelöscht wurde. Wenn nur Standard-Abschnitte (Textfeld oder Navigationsleiste für Unterabschnitte)
                    erstellt werden sollen, kann die Option ignoriert werden.
                </p>
                {{-- Name --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="section_name{{ $section->id }}">(Vairablen-)Name des Abschnitts</label>
                    <input class="form-control" type="text" size="100" id="section_name{{ $section->id }}" name="name"
                           form="formAddSection{{ $section->id }}" placeholder="eindeutiger Variablenname" required/>
                </div>
                {{-- Überschrift des Abschnitts --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="section_heading{{ $section->id }}">(Endgültige) Überschrift des Abschnitts</label>
                    <input class="form-control" type="text" id="section_heading{{ $section->id }}" name="heading"
                           placeholder="endgültige Überschrift" required form="formAddSection{{ $section->id }}"/>
                </div>
                {{-- Name des Templates --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="section_tpl{{ $section->id }}">
                        Das zu verwendende Template
                    </label>
                    <select class="form-control" form="formAddSection{{ $section->id }}" name="tpl" id="section_tpl{{ $section->id }}">
                        @foreach(\App\Section::TEMPLATES as $tpl)
                            <option value="{{ $tpl }}" @if($tpl == 'text_section') selected @endif>{{ $tpl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" id="formAddSection{{ $section->id }}" method="POST"
                      action="{{ route('abschlussprojekt.dokumentation.abschnitte.create', $documentation->project) }}">
                    @csrf
                    <input type="hidden" name="section_id" value="{{ $section->id }}">
                    <button type="submit" class="btn btn-primary">Unterabschnitt hinzufügen</button>
                </form>
            </div>
        </div>
    </div>
</div>
