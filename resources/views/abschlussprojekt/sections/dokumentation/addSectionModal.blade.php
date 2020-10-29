{{-- Modales Fenster zum Hinzufügen eines Unterabschnitts --}}

<div class="modal fade section-modal" id="addSection{{ $section ? $section->id : '' }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                    Die Option "Nummerierung" bestimmt, wie der Abschnitt im englültigen PDF-DOkument nummeriert wird.
                    Option "Keine Nummerierung" bedeutet, dass der Abschnitt nicht nummeriert wird. Option "Standard
                    Nummerierung" bedeutet, dass der Abschnitt normal nummeriert wird. Im Normalfall sollten Sie diese
                    Option wählen. Option "Anhang Nummerierung" bedeutet, dass der Abschnitt separat nummeriert wird.
                    Im Normalfall sollten Sie diese Option nur für den Anhang wählen.<br/>
                    Beachten Sie: Für Unterabschnitte wird die Nummerierung ihres Überabschnitts verwendet.<br/>
                    Die Option "Template" existiert hauptsächlich, falls versehentlich ein nicht Standard-Abschnitt
                    gelöscht wurde. Wenn nur Standard-Abschnitte (Textfeld oder Navigationsleiste für Unterabschnitte)
                    erstellt werden sollen, kann die Option ignoriert werden.
                </p>
                {{-- Überschrift des Abschnitts --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="section_heading{{ $section ? $section->id : '' }}">
                        (Endgültige) Überschrift des Abschnitts
                    </label>
                    <input class="form-control" type="text" id="section_heading{{ $section ? $section->id : '' }}" name="heading"
                           placeholder="endgültige Überschrift" required form="formAddSection{{ $section ? $section->id : '' }}"/>
                </div>
                <b>Die folgenden Einstellungen können im Normalfall ignoriert werden.</b>
                {{-- Name --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="section_name{{ $section ? $section->id : '' }}">
                        (Vairablen-)Name des Abschnitts; Einstellung kann im Normalfall ignoriert werden.
                    </label>
                    <input class="form-control" type="text" size="100" id="section_name{{ $section ? $section->id : '' }}" name="name"
                           form="formAddSection{{ $section ? $section->id : '' }}" placeholder="eindeutiger Variablenname"/>
                </div>
                {{-- Name des Templates --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="section_tpl{{ $section ? $section->id : '' }}">
                        Das zu verwendende Template; Einstellung kann im Normalfall ignoriert werden.
                    </label>
                    <select class="form-control" form="formAddSection{{ $section ? $section->id : '' }}" name="tpl"
                            id="section_tpl{{ $section ? $section->id : '' }}">
                        @foreach(\App\Section::TEMPLATES as $tpl)
                            <option value="{{ $tpl }}" @if($tpl == 'text_section') selected @endif>{{ $tpl }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Welcher Nummerierung gehört der Abschnitt an? (keine, normales Inhaltsverzeichnis, Anhang --}}
                <div class="form-group mt-3 mb-3">
                    <label class="control-label" for="section_counter{{ $section ? $section->id : '' }}">
                        Die gültige Nummerierung; Einstellung kann im Normalfall ignoriert werden.
                    </label>
                    <select class="form-control" @if($section) disabled @endif form="formAddSection{{ $section ? $section->id : '' }}"
                            name="counter" id="section_counter{{ $section ? $section->id : '' }}">
                        <option value="none" @if($section && $section->counter == 'none') selected @endif>Keine Nummerierung</option>
                        <option value="inhalt" @if(($section && $section->counter == 'inhalt') || ! $section) selected @endif>Standard Nummerierung</option>
                        <option value="anhang" @if($section && $section->counter == 'anhang') selected @endif>Anhang Nummerierung</option>
                    </select>
                    {{-- Da das Feld counter bei der Validierung notwendig ist --}}
                    @if($section)
                        <input type="hidden" name="counter" value="{{ $section->counter }}" form="formAddSection{{ $section->id }}"/>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" id="formAddSection{{ $section ? $section->id : '' }}" method="POST"
                      action="{{ route('abschlussprojekt.dokumentation.abschnitte.create', $documentation->project) }}">
                    @csrf
                    <input type="hidden" name="section_id" value="{{ $section ? $section->id : 0 }}">
                    <button type="submit" class="btn btn-primary">Unterabschnitt hinzufügen</button>
                </form>
            </div>
        </div>
    </div>
</div>
