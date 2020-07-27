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

@if(request()->is('*antrag') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
