{{-- Formularteil für Projektphasen; nicht ausfüllbar, Werte werden in Antrag bestimmt. --}}

<table class="table table-striped">
    <tr>
        <th>Projektphase</th>
        <th>Geplante Zeit</th>
    </tr>
    @foreach($documentation->project->getPhasesDuration() as $phase)
        <tr>
            <td>{{ $phase['heading'] }}</td>
            <td>{{ $phase['duration'] }} h</td>
        </tr>
    @endforeach
</table>
