{{----}}

<h4>Version: {{ $version->updated_at }}, geändert von {{ $version->user->full_name }}</h4>
{{-- Navigationsleiste der Version --}}
@include('abschlussprojekt.antrag.navigationsleiste')
{{-- Tabinhalt Version 0 --}}
@include('abschlussprojekt.antrag.tabinhalt', [
    'disable' => true,
 ])
<div class="row">
    {{-- Link zum Löschen der Version --}}
    <div class="col-6 text-left p-3">
        <a class="btn btn-outline-danger" href="#deleteVersion{{ $v_name }}" data-toggle="modal">Version löschen</a>
    </div>
    {{-- Formular zum Speichern der Version als aktuelle Version --}}
    <form class="form col-6 text-right p-3" action="{{ route('abschlussprojekt.antrag.use_version', $proposal->project) }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $version->id }}"/>
        <input class="btn btn-primary" type="submit" value="Version übernehmen"/>
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

                <form class="form" action="{{ route('abschlussprojekt.antrag.delete_version', $proposal->project) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $version->id }}"/>
                    <button type="submit" class="btn btn-danger">Löschen</button>
                </form>
            </div>
        </div>
    </div>
</div>
