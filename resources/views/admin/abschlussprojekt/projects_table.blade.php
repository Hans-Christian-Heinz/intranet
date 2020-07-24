{{-- Tabelle aller Abschlussprojekte in einem Jahr --}}

<table class="table table-striped">
    <thead>
    <tr>
        <th>Auszubildende(r)</th>
        <th>Fachrichtung</th>
        <th>Thema</th>
        <th>Betreuer(in)</th>
        <th>Antrag</th>
        <th>Dokumentation</th>
    </tr>
    </thead>
    <tbody>
    @foreach($fachrichtungen as $fachrichtung => $projekte)
        <tr>
            <th></th>
            <th>{{ $fachrichtung }}</th>
            <th colspan="4"></th>
        </tr>
        @foreach ($projekte as $project)
            <tr>
                <td>{{ $project->user->full_name }}</td>
                <td>{{ $project->user->fachrichtung }}</td>
                <td>{{ $project->topic }}</td>
                <td>{{ $project->supervisor ? $project->supervisor->full_name : 'Nicht zugewiesen' }}</td>
                <td>
                    <a class="btn btn-secondary" href="{{ route('admin.abschlussprojekt.antrag.index', ['project' => $project]) }}">
                        Antrag
                    </a>
                </td>
                <td>
                    <a class="btn btn-secondary" href="{{ route('admin.abschlussprojekt.dokumentation.index', ['project' => $project]) }}">
                        Dokumentation
                    </a>
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
