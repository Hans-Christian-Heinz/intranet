{{-- Inputs zum Generieren eines Platzhalters für Links --}}

<p class="text-justify">
    Fügen Sie Ihrem dem PDF-Dokument interne Links zu seinen Überschriften hinzu: Wählen Sie die Überschrift
    aus, auf die verwiesen werden soll, geben Sie den Text des Links ein und kopieren Sie das Ergebnis
    an die Stelle, an der der Link stehen soll:
</p>
<div class="my-3">
    <label class="control-label" for="insert_link_target">Ziel des Links</label>
    <select class="form-control" id="insert_link_target">
        @foreach($sectionsHelp as $section)
            <option id="insert_link_target_{{ $section->id }}" value="{{ $section->name }}">
                {{ $section->heading }}
            </option>
        @endforeach
    </select>
</div>
<div class="my-3">
    <label class="control-label" for="insert_link_text">Text des Links</label>
    <input class="form-control" type="text" id="insert_link_text" value="{{ $sectionsHelp->first()->heading }}"/>
</div>
<div class="my-3">
    <label class="control-label" for="insert_placeholder">Platzhalter</label>
    <input type="text" class="form-control" id="insert_placeholder" disabled
           value="Placeholder"/>
</div>
