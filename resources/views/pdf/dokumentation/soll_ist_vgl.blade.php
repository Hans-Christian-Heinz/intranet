{{----}}

<p class="abschnitt">{!! nl2br(e($zeitplanung['text'])) !!}</p>

<table>
    <tr class="bgHeader">
        <th>Projektphase</th>
        <th>Geplant</th>
        <th>Tatsächlich</th>
        <th>Differenz</th>
    </tr>
    @foreach($documentation->getPhasesDifference() as $name => $phase)
        @if($name !== 'gesamt')
            <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
                <td>{{ $phase['heading'] }}</td>
                <td>{{ $phase['duration'] }}h</td>
                <td>{{ $zeitplanung[$name] }}h</td>
                <td>{{ $phase['difference'] }}</td>
            </tr>
        @else
            <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
                <th>{{ $phase['heading'] }}</th>
                <th>{{ $phase['duration'] }}</th>
                <th>{{ $zeitplanung[$name] }}</th>
                <th>{{ $phase['difference'] }}</th>
            </tr>
        @endif
    @endforeach
</table>
