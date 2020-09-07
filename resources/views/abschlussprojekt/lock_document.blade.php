{{-- Schaltflächen zum Sperren und Freigeben von Dokumenten (damit ein Dokument nur von einem Benutzer auf einmal
     bearbeitet werden kann. --}}

<div class="d-flex mb-3">
    @if(app()->user->is($document->lockedBy))
        <p>Das Dokument wird im Moment von Ihnen bearbeitet. Es ist für andere Benutzer gesperrt.</p>
        <form method="post" class="form mx-4" action="{{ route($route_prefix . '.release', [
            'project' => $document->project,
            $route_param => $document,
        ]) }}">
            @csrf
            @method('patch')
            <input type="submit" class="btn btn-secondary" value="Dokument freigeben."/>
        </form>
    @else
        @isset($document->lockedBy)
            <p>
                Das Dokument wird im Moment von {{ $document->lockedBy->full_name }} bearbeitet.
                Sie können es erst bearbeiten, wenn es freigegeben wurde.
            </p>
        @else
            <p>
                Möchten Sie das Dokument bearbeiten und somit für andere Benutzer sperren?
            </p>
            <form method="post" class="form mx-4" action="{{ route($route_prefix . '.lock', [
                'project' => $document->project,
                $route_param => $document,
            ]) }}">
                @csrf
                @method('patch')
                <input type="submit" class="btn btn-secondary" value="Dokument bearbeiten."/>
            </form>
        @endisset
    @endif
</div>
