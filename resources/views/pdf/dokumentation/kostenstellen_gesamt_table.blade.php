{{----}}

<div style="page-break-inside: avoid">
    <tocentry content="Tabelle {{ $table_nr->nextNumber() }}: Ressourcenplanung allgemein" name="toc_tables"/>
    <table>
        <caption>Tabelle {{ $table_nr->getNumber() }}: Ressourcenplanung allgemein</caption>
        <tr class="bgHeader">
            <th>Beschreibung</th>
            <th>Kosten</th>
        </tr>
        @foreach($kostenstellen_gesamt as $name => $prize)
            <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
                @if($loop->last)
                    <td><b>{{ $name }}</b></td>
                    <td><b>{{ $prize }}€</b></td>
                @else
                    <td>{{ $name }}</td>
                    <td>{{ $prize }}€</td>
                @endif
            </tr>
        @endforeach
    </table>
</div>
