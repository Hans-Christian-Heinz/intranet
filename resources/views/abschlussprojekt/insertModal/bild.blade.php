{{-- Abschnitt zum Hinzufügen einer Image-Instanz zu einem Abschnitt --}}

<table class="table">
    <tr>
        <td colspan="2">
            <p class="text-justify">
                Wählen Sie ein Bild und einen Abschnitt aus, um das Bild dem Abschnitt hinzuzufügen.<br/>
                Beachten Sie: Bilder müssen zunächst im separaten Abschnitt Bilder (Navigationsleiste: "Abschlussprojetkt",
                Option "Bilder") hochgeladen werden.<br/>
                Beachten Sie: Bilder werden nur in denjenigen Abschnitten angezeigt, die durch Fließtext beschrieben
                werden. (Bilder werden nicht angezeigt in den folgenden Abschnitten: "Titel", "Projektphasen",
                "Ressourcenplanung" (alle Unterabschnitte).)<br/>
                Beachten Sie: Bilder werden standardmäßig am Ende eines Abschnitts eingefügt. Wenn Sie innerhalb eines
                Abschnitts eingefügt werden sollen, kennzeichnen Sie diese Stellen bitte mit dem Platzahlter ##IMAGE()##.
            </p>
        </td>
    </tr>
    <tr>
        <td><label for="img_section">Abschnitt wählen:</label></td>
        <td>
            <select form="insertImageForm" class="form-control @error('img_section') is-invalid @enderror" name="section_id"
                    id="img_section" required>
                @foreach($version->sections->sortBy('heading') as $section)
                    {{-- Stelle sicher, dass nur Abschnitte ohne Unterabschnitt und mit Fließtext ausgewählt werden können --}}
                    @if($version->sections()->where('sections.section_id', $section->id)->count() == 0 &&
                        ($section->tpl == 'text_section' || $section->tpl == 'dokumentation.vgl_section' || $section->tpl == 'tinymce_section')
                        && ! $section->is_locked)
                        <option id="section_image_{{ $section->name }}" value="{{ $section->id }}">
                            {{ $section->heading }}
                        </option>
                    @endif
                @endforeach
            </select>
            @error('img_section') <p class="invalid-feedback">{{ $message }}</p> @enderror
        </td>
    </tr>
    <tr>
        <td>
            <div class="dropdown">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" id="dropdownImageButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bild
                </button>
                <div class="dropdown-menu  @error('img') is-invalid @enderror" aria-labelledby="dropdownImageButton">
                    @foreach(app()->user->getImageFiles() as $i => $file)
                    {{--@foreach($availableImages as $i => $file)--}}
                        <div class="dropdown-item radio-group">
                            <input type="radio" name="path" form="insertImageForm" value="{{ $file }}"
                                   class="radioImage" required id="radio_{{ $i }}"/>
                            <label for="radio_{{ $i }}">
                                <img src="{{ asset('storage/' . $file) }}" height="150" width="240"
                                     alt="Im gespeicherten Dateipfad liegt keine Bilddatei." id="img_{{ $i }}"/>
                                {{--<img src="{{ $file }}" height="150" width="240"
                                     alt="Im gespeicherten Dateipfad liegt keine Bilddatei." id="img_{{ $i }}"/>--}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            @error('img') <p class="invalid-feedback">{{ $message }}</p> @enderror
        </td>
        <td>
            <img src="" alt="Kein Bild ausgewählt" height="75" width="120" id="bild_gewaehlt"/>
        </td>
    </tr>
    <tr>
        <td><label for="footnote">Fußnote:</label></td>
        <td>
            <input type="text" id="footnote" name="footnote" form="insertImageForm" placeholder="Fußnote"
                   class="form-control @error('footnote') is-invalid @enderror"/>
            @error('footnote') <p class="invalid-feedback">{{ $message }}</p> @enderror
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <form class="form" id="insertImageForm" method="POST"
                  action="{{ route('abschlussprojekt.dokumentation.images.create', $documentation->project) }}">
                @csrf
                <button type="submit" @if($disable) disabled @endif class="btn btn-primary">Bild hinzufügen</button>
            </form>
        </td>
    </tr>
</table>
