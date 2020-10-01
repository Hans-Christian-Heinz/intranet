{{-- Tabelle aller Ausbzubildenden mit demselben Ausbildungsbeginn --}}

<table class="table table-striped text-center">
    <thead>
    <tr>
        <th>Auszubildene(r)</th>
        <th>Wochen seit Ausbildungsbeginn</th>
        <th>Vorhandene Berichtshefte</th>
        <th>Fehlende Berichtshefte</th>
        <th>Berichtshefte ansehen</th>
    </tr>
    </thead>
    <tbody>
    @foreach($azubi_liste as $azubi)
        <tr>
            <td>{{ $azubi->full_name }}</td>
            @if(is_null($azubi->ausbildungsbeginn))
                <td colspan="3">Es wurden noch keine Berichtshefte angelegt.</td>
            @else
                <td>{{ $azubi->criteria['dauer'] }}</td>
                <td>{{ $azubi->criteria['anzahl'] }}</td>
                <td>{{ $azubi->criteria['fehlend'] }}</td>
            @endif
            <td>
                <a class="btn btn-sm btn-secondary" href="{{ route('admin.berichtshefte.liste', $azubi) }}">Ansehen</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- $azubi_liste->links() --}}
