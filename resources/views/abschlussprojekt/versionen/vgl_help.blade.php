{{-- Das Formular für ein Dokument; wird 2 mal angezeigt beim Vergleich von Versionen --}}

<h4>Version: {{ $version->updated_at }}, geändert von {{ $version->user->full_name }}</h4>

<div class="row">
    @if($doc_type == 'antrag')
        <div class="col-3">
            @include('abschlussprojekt.sections.sectionNavigation', ['proposal' => $document, 's' => $document, 'name' => '', 'disable' => true])
        </div>
        <div class="col-9 tab-content">
            @include('abschlussprojekt.sections.tabinhaltNeu', [
                'proposal' => $document,
                'disable' => true,
                's' => $document,
                'form' => '',
                'availableSections' => $version->availableSections,
            ])
        </div>
    @else
        <div class="col-3">
            @include('abschlussprojekt.sections.sectionNavigation', [
                'documentation' => $document,
                's' => $document,
                'name' => '',
                'disable' => true,
            ])
        </div>
        <div class="col-9 tab-content">
            @include('abschlussprojekt.sections.tabinhaltNeu', [
                'documentation' => $document,
                'disable' => true,
                's' => $document,
                'form' => '',
                'availableSections' => $version->availableSections,
            ])
        </div>
    @endif
</div>

{{--@if($doc_type == 'antrag')
    {{-- Navigationsleiste der Version -}}
    @include('abschlussprojekt.antrag.navigationsleiste', ['disable' => true, 'proposal' => $document,])
    {{-- Tabinhalt Version 0 -}}
    @include('abschlussprojekt.antrag.tabinhalt', [
        'disable' => true,
        'proposal' => $document,
     ])
@else
    {{-- Navigationsleiste der Version -}}
    @include('abschlussprojekt.dokumentation.navigationsleiste', ['disable' => true, 'documentation' => $document])
    {{-- Tabinhalt Version 0 -}}
    @include('abschlussprojekt.dokumentation.tabinhalt', [
        'disable' => true,
        'documentation' => $document,
     ])
@endif--}}
<div class="row">
    {{-- Link zum Löschen der Version --}}
    <div class="col-6 text-left p-3">
        <a class="btn btn-outline-danger" href="#deleteVersion{{ $v_name }}" data-toggle="modal">Version löschen</a>
    </div>
    {{-- Formular zum Speichern der Version als aktuelle Version --}}
    <form class="form col-6 text-right p-3" method="post"
          @if(request()->is('admin*'))
            action="{{ route('admin.abschlussprojekt.versionen.use_version', ['project' => $document->project, 'doc_type' => $doc_type,]) }}"
          @else
            action="{{ route('abschlussprojekt.versionen.use_version', ['project' => $document->project, 'doc_type' => $doc_type,]) }}"
          @endif>
        @csrf
        <input type="hidden" name="id" value="{{ $version->id }}"/>
        <input class="btn btn-primary" @if($document->vc_locked) disabled @endif type="submit" value="Version übernehmen"/>
    </form>
</div>

<div class="modal fade" id="deleteVersion{{ $v_name }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Version löschen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">Möchten Sie die Version {{ $version->updated_at }} wirklich löschen?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" method="POST"
                      @if(request()->is('admin*'))
                        action="{{ route('admin.abschlussprojekt.versionen.delete_version', ['project' => $document->project, 'doc_type' => $doc_type,]) }}"
                      @else
                        action="{{ route('abschlussprojekt.versionen.delete_version', ['project' => $document->project, 'doc_type' => $doc_type,]) }}"
                      @endif>
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $version->id }}"/>
                    <button type="submit" class="btn btn-danger" @if($document->vc_locked && $version->is($document->latestVersion())) disabled @endif>
                        Löschen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
