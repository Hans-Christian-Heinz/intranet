{{-- Modales Fenster zum Hinzufügen eines Bildes zur Projektdokumentation --}}

<div class="modal fade" id="addImage" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bild hinzufügen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td colspan="2">
                            <p class="text-justify">
                                Wählen Sie ein Bild und einen Abschnitt aus, um das Bild dem Abschnitt hinzuzufügen.<br/>
                                Beachten Sie: Bilder müssen zunächst im separaten Abschnitt Bilder (Navigationsleiste: "Abschlussprojetkt",
                                Option "Bilder") hochgeladen werden.<br/>
                                Beachten Sie: Bilder werden nur in denjenigen Abschnitten angezeigt, die durch Fließtext beschrieben
                                werden. (Bilder werden nicht angezeigt in den folgenden Abschnitten: "Titel", "Projektphasen",
                                "ressourcenplanung" (alle Unterabschnitte).)
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="img_section">Abschnitt wählen:</label></td>
                        <td>
                            <select form="addImageForm" class="form-control @error('img_section') is-invalid @enderror" name="section_id"
                                    id="img_section" required>
                                @foreach($version->sections->sortBy('heading') as $section)
                                    {{-- Stelle sicher, dass nur Abschnitte ohne Unterabschnitt und mit Fließtext ausgewählt werden können --}}
                                    @if($version->sections()->where('sections.section_id', $section->id)->count() == 0 &&
                                        ($section->tpl == 'text_section' || $section->tpl == 'dokumentation.vgl_section'))
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
                                        <div class="dropdown-item radio-group">
                                            <input type="radio" name="path" form="addImageForm" value="{{ $file }}"
                                                   class="radioImage" required id="radio_{{ $i }}"/>
                                            <label for="radio_{{ $i }}">
                                                <img src="{{ asset('storage/' . $file) }}" height="150" width="240"
                                                     alt="Im gespeicherten Dateipfad liegt keine Bilddatei." id="img_{{ $i }}"/>
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
                            <input type="text" id="footnote" name="footnote" form="addImageForm" placeholder="Fußnote"
                                   class="form-control @error('footnote') is-invalid @enderror"/>
                            @error('footnote') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" id="addImageForm" method="POST"
                      action="{{ route('abschlussprojekt.dokumentation.images.create', $documentation->project) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Bild hinzufügen</button>
                </form>
            </div>
        </div>
    </div>
</div>
