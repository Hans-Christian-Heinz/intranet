{{----}}

<div style="page-break-inside: avoid">
    <tocentry content="Tabelle {{ $table_nr->nextNumber() }}: grobe Zeitplanung" name="toc_tables"/>
    <table>
        <caption>Tabelle {{ $table_nr->getNumber() }}: grobe Zeitplanung</caption>
        @foreach($version->sections()->where('sections.section_id', $section->id)->orderBy('sequence')->get() as $phase)
            @php($help = $phase->getPhases())

            <tr class="bgHeader">
                <th>{{ $phase->heading }}</th>
                <th>{{ $help['gesamt']->duration }}h</th>
            </tr>
            @foreach($help as $key => $val)
                @if($key === 'gesamt')
                    @continue
                @endif
                <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->duration }}h</td>
                </tr>
            @endforeach
        @endforeach
    </table>
</div>
