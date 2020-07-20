{{----}}

@php($zeitplanung = $project->getPhasesDuration())

<table>
    <tr class="bgHeader">
        <th>Projektphase</th>
        <th>Geplante Zeit</th>
    </tr>
    @foreach($zeitplanung as $phase)
        <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
            <td>{{ $phase['heading'] }}</td>
            <td>{{ $phase['duration'] }}h</td>
        </tr>
    @endforeach
</table>
