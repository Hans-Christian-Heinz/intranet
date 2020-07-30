{{----}}

<div style="page-break-inside: avoid">
    <tocentry content="Tabelle {{ $table_nr->nextNumber() }}: Ressourcenplanung allgemein" name="toc_tables"/>
    <table>
        <tr class="bgHeader">
            <th>Beschreibung</th>
            <th>Kosten</th>
        </tr>
        @foreach($kostenstellen_gesamt as $name => $prize)
            <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
                @if($loop->last)
                    <th>{{ $name }}</th>
                    <th>{{ number_format($prize, 2) }}€</th>
                @else
                    <td>{{ $name }}</td>
                    <td>{{ number_format($prize, 2) }}€</td>
                @endif
            </tr>
        @endforeach
    </table>
    <span class="footnote">Tabelle {{ $table_nr->getNumber() }}: Ressourcenplanung allgemein</span>
</div>
