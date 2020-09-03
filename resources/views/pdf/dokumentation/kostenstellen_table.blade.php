{{----}}

<div style="page-break-inside: avoid">
    <tocentry content="Tabelle {{ $table_nr->nextNumber() }}: Ressourcenplanung {{ $section->heading }}" name="toc_tables"/>
    <table>
        <tr class="bgHeader">
            <th>{{ $section->heading }}</th>
            <th>Beschreibung</th>
            <th>Kosten</th>
        </tr>
        @foreach($kostenstellen[$section->heading] as $ks)
            <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
                <td>{{ $ks->name }}</td>
                <td>{{ $ks->description }}</td>
                <td>{{ number_format($ks->prize, 2) }}€</td>
            </tr>
            @if($loop->last)
                <tr class="@if($loop->index % 2 != 0) bg0 @else bg1 @endif">
                    <th>Summe</th>
                    <th></th>
                    <th>{{ $kostenstellen_gesamt[$section->heading] }}€</th>
                </tr>
            @endif
        @endforeach
    </table>
    <span class="footnote">Tabelle {{ $table_nr->getNumber() }}: Ressourcenplanung {{ $section->heading }}</span>
</div>
