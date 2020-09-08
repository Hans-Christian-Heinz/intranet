{{-- Generieren von Platzhaltern für in eine Dokumentation einzubindende Dokumente sowie das Hochladen solcher Dokumente --}}
{{-- Wird im Moment nicht verwendet und ist nicht fertig. --}}

<p class="text-justify">
    Sie können andere PDF-Dokumente in Ihre Dokumentation einbinden. DIese Option ist für den Anhang der Dokumentation geplant.
    So können Sie zum Beispiel eine fertige Benutzer- oder Kundendokumentation oder andere Dokumente einbinden.<br/>
    Wählen Sie ein bereits hochgeladenes Dokument aus und kopieren Sie den generierten Platzhalter an die gewünschte Stelle Ihrer Dokumentation.
</p>
<div class="my-3">
    <label class="control-label" for="insert_dokument">Bereits verfügbare Dokumente:</label>
    <select class="form-control" id="insert_dokument">
        @foreach(app()->user->getUploadedDocuments() as $i => $doc)
            <option value="{{ $doc }}">{{ $doc }}</option>
        @endforeach
    </select>
</div>

<div class="my-3">
    <label class="control-label" for="insert_document_placeholder">Platzhalter</label>
    <input type="text" class="form-control" id="insert_document_placeholder" disabled
           value="Placeholder"/>
</div>

<div class="py-3 border-top">
    <h5>Dokument hochladen</h5>

    <form class="form" action="{{ route('abschlussprojekt.dokumente.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="input-group">
            <div class="custom-file">
                <input type="file" id="upload_document" required name="document" class="custom-file-input @error('document') is-invalid @enderror"
                       accept="application/pdf"/>
                <label class="custom-file-label" for="upload_document">Dokument</label>
                @error('document') <p class="invalid-feedback">{{ $message }}</p> @enderror
            </div>
        </div>
        <input type="submit" class="btn btn-primary my-2 float-right" value="Hochladen"/>
    </form>
</div>
