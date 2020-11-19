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
                <td>
                    <form action="{{ route('admin.abschlussprojekt.betreuer', $project) }}" method="post">
                        @csrf
                        @method('patch')
                        <select class="form-control" name="supervisor_id" onchange="$(this).parent('form').submit();">
                            <option value="0" class="text-center" @if(! $project->supervisor) selected @endif>-</option>
                            @foreach ($admins as $admin)
                                <option value="{{ $admin->id }}" @if($admin->is($project->supervisor)) selected @endif>
                                    {{ $admin->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td>
                    <a class="btn btn-secondary" @isset($project->proposal) href="{{ route('admin.abschlussprojekt.antrag.index', $project) }}"
                       @else href="{{ route('admin.abschlussprojekt.antrag.create', $project) }}" @endisset>
                        Antrag
                    </a>
                </td>
                <td>
                    <a class="btn btn-secondary" @isset($project->documentation) href="{{ route('admin.abschlussprojekt.dokumentation.index', $project) }}"
                       @else href="{{ route('admin.abschlussprojekt.dokumentation.create', $project) }}" @endisset>
                        Dokumentation
                    </a>
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
