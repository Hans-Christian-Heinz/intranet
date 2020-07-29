{{----}}

@php($sectionsHelp = $version->sections->sortBy("heading"))

<div class="modal fade" id="generateLinks" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Links generieren</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-justify">
                    Fügen Ihrem dem PDF-Dokument interne Links zu seinen Überschriften hinzu: Wählen Sie die Überschrift
                    aus, auf die verwiesen werden soll, geben Sie den Text des Links ein und kopieren Sie das Ergebnis
                    an die Stelle, an der der Link stehen soll:
                </p>
                <div class="form-group my-3">
                    <label class="control-label" for="link_target">Ziel des Links</label>
                    <select class="form-control" id="link_target">
                        @foreach($sectionsHelp as $section)
                            <option id="link_target_{{ $section->id }}" value="{{ $section->name }}">
                                {{ $section->heading }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group my-3">
                    <label class="control-label" for="link_text">Text des Links</label>
                    <input class="form-control" type="text" id="link_text" value="{{ $sectionsHelp->first()->heading }}"/>
                </div>
            </div>
            <div class="modal-footer">
                <label class="control-label" for="generated_link">Link (Platzhalter)</label>
                <input type="text" class="form-control" id="generated_link" disabled
                       value="##LINK({{ $sectionsHelp->first()->name . ', ' . $sectionsHelp->first()->heading }})##"/>
            </div>
        </div>
    </div>
</div>
