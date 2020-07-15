{{-- Formularteil für Projektphasen; nicht ausfüllbar, Werte werden in Antrag bestimmt. --}}

<table class="table table-striped">
    <tr>
        <th>Projektphase</th>
        <th>Geplante Zeit</th>
    </tr>
    @foreach(\App\Proposal::PHASES as $phase)
        <tr>
            <td>{{ $phase['heading'] }}</td>
            <td>TODO h</td>
        </tr>
    @endforeach
    <tr>
        <td><b>Gesamt</b></td>
        <td><b>TODO h</b></td>
    </tr>
</table>
