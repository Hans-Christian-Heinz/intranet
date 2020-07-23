{{-- Modales Fenster zum Bearbeiten eines Abschnitts --}}

<div class="modal fade" id="editSection{{ $section->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Abschnitt bearbeiten</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Name --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="edit_name{{ $section->id }}">(Vairablen-)Name des Abschnitts</label>
                    <input class="form-control" type="text" size="100" id="edit_name{{ $section->id }}" name="name"
                           form="formEditSection{{ $section->id }}" placeholder="eindeutiger Variablenname"
                           required value="{{ $section->name }}"/>
                </div>
                {{-- Überschrift des Abschnitts --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="edit_heading{{ $section->id }}">(Endgültige) Überschrift des Abschnitts</label>
                    <input class="form-control" type="text" id="edit_heading{{ $section->id }}" name="heading" value="{{ $section->heading }}"
                           placeholder="endgültige Überschrift" required form="formEditSection{{ $section->id }}"/>
                </div>
                {{-- Name des Templates --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="edit_tpl{{ $section->id }}">
                        Das zu verwendende Template
                    </label>
                    <select class="form-control" form="formEditSection{{ $section->id }}" name="tpl" id="edit_tpl{{ $section->id }}">
                        @foreach(\App\Section::TEMPLATES as $tpl)
                            <option value="{{ $tpl }}" @if($tpl == $section->tpl) selected @endif>{{ $tpl }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Position des Abschnitts --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="edit_sequence{{ $section->id }}">
                        Die Position des Abschnitts
                    </label>
                    <input type="number" class="form-control" form="formEditSection{{ $section->id }}" name="sequence"
                           id="edit_sequence{{ $section->id }}" value="{{ $section->pivot->sequence }}" min="0"
                           step="1" max="{{ $section->getParent()->getSections($version)->count() - 1 }}"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" id="formEditSection{{ $section->id }}" method="POST"
                      action="{{ route('abschlussprojekt.dokumentation.abschnitte.edit',
                                ['project' => $documentation->project, 'section' => $section]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                </form>
            </div>
        </div>
    </div>
</div>
