{{----}}

<div style="page-break-inside: avoid">
    <tocentry content="Tabelle {{ $table_nr->nextNumber() }}: Ressourcenplanung {{ $section->heading }}" name="toc_tables"/>
    <table>
        <caption>Tabelle {{ $table_nr->getNumber() }}: Ressourcenplanung {{ $section->heading }}</caption>
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
                    <td><b>Summe</b></td>
                    <td><b></b></td>
                    <td><b>{{ $kostenstellen_gesamt[$section->heading] }}€</b></td>
                </tr>
            @endif
        @endforeach
    </table>
</div>
