{{-- Formularteil für Projektphasen; nicht ausfüllbar, Werte werden in Antrag bestimmt. --}}

<table class="table table-striped">
    <tr>
        <th>Projektphase</th>
        <th>Geplante Zeit</th>
    </tr>
    @foreach($documentation->project->getPhasesDuration() as $heading => $duration)
        <tr>
            <td>{{ $heading }}</td>
            <td>{{ $duration }} h</td>
        </tr>
    @endforeach
</table>
