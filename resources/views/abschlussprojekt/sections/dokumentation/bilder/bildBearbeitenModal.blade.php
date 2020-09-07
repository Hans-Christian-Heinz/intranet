{{-- Modales Fenster zum Bearbeiten einer Image-Instanz --}}

<div class="modal fade" id="bildBearbeiten{{ $image->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bild bearbeiten</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <tr>
                        <td><label for="footnote{{ $image->id }}">Fußnote:</label></td>
                        <td>
                            <input type="text" id="footnote{{ $image->id }}" name="footnote" form="changeImageForm{{ $image->id }}" placeholder="Fußnote"
                                   class="form-control @error('footnote') is-invalid @enderror" value="{{ $image->footnote }}"/>
                            @error('footnote') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="sequence{{ $image->id }}">Position:</label></td>
                        <td>
                            <input type="number" id="sequence{{ $image->id }}" name="sequence" min="0" max="{{ $s->images->count() - 1 }}"
                                   class="form-control @error('sequence') is-invalid @enderror" form="changeImageForm{{ $image->id }}"
                                   value="{{ $image->pivot->sequence }}"/>
                            @error('sequence') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="width{{ $image->id }}">Breite:</label></td>
                        <td>
                            <input type="number" id="width{{ $image->id }}" name="width" min="100" max="1500" step="1"
                                   class="form-control @error('width') is-invalid @enderror" form="changeImageForm{{ $image->id }}"
                                   value="{{ $image->width }}"/>
                            @error('width') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="height{{ $image->id }}">Höhe:</label></td>
                        <td>
                            <input type="number" id="height{{ $image->id }}" name="height" min="100" max="1500" step="1"
                                   class="form-control @error('height') is-invalid @enderror" form="changeImageForm{{ $image->id }}"
                                   value="{{ $image->height }}"/>
                            @error('height') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <a type="button" class="btn btn-link text-secondary" target="_blank"
                   href="{{ route('abschlussprojekt.bilder.vorschau', ['project' => $documentation->project, 'image' => $image]) }}">
                    Vorschau
                </a>

                <form class="form" id="changeImageForm{{ $image->id }}" method="POST"
                      action="{{ route('abschlussprojekt.dokumentation.images.update', $documentation->project) }}">
                    @csrf
                    <input type="hidden" name="img_id" value="{{ $image->id }}"/>
                    <input type="hidden" name="section_id" value="{{ $s->id }}"/>
                    <button type="submit" @if($s->is_locked) disabled @endif class="btn btn-primary">Bild speichern</button>
                </form>
            </div>
        </div>
    </div>
</div>
