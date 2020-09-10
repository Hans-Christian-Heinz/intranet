{{----}}

@php($zeitplanung = $project->getPhasesDuration())

<div style="page-break-inside: avoid">
    <tocentry content="Tabelle {{ $table_nr->nextNumber() }}: grobe Zeitplanung" name="toc_tables"/>
    <table>
        <caption>Tabelle {{ $table_nr->getNumber() }}: grobe Zeitplanung</caption>
        <tr class="bgHeader">
            <th>Projektphase</th>
            <th>Geplante Zeit</th>
        </tr>
        @foreach($zeitplanung as $phase)
            <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
                @if($loop->last)
                    <td><b>{{ $phase['heading'] }}</b></td>
                    <td><b>{{ $phase['duration'] }}h</b></td>
                @else
                    <td>{{ $phase['heading'] }}</td>
                    <td>{{ $phase['duration'] }}h</td>
                @endif
            </tr>
        @endforeach
    </table>
</div>
